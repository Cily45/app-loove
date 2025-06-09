<?php

namespace App\Models;

class MessagesModel extends BaseModel
{
    public function allMessageById(
        int $id
    ): array
    {
        return $this
            ->query("
            SELECT rm.message, rm.date, rm.hour, rm.is_view,
                   rm.sender_id = :id AS is_that_user,
                   u.id, u.firstname, u.lastname, u.profil_photo
            FROM (
                SELECT m.*,
                       CASE WHEN sender_id = :id THEN receiver_id ELSE sender_id END AS other_user_id,
                       ROW_NUMBER() OVER (
                           PARTITION BY CASE WHEN sender_id = :id THEN receiver_id ELSE sender_id END
                           ORDER BY date DESC, hour DESC
                       ) AS rn
                FROM messages m
                WHERE sender_id = :id OR receiver_id = :id
            ) rm
            JOIN user u ON u.id = rm.other_user_id
            WHERE rm.rn = 1
            ORDER BY rm.date DESC, rm.hour DESC;
            ")
            ->fetchAll([
                'id' => $id
            ]);
    }

    public function allMessageByIdById(
        int $id,
        int $id2
    ): array
    {
        return $this
            ->query("
            SELECT *, 
                   CASE WHEN sender_id = :id THEN 1 ELSE 0 END AS is_that_user
            FROM messages
            WHERE (sender_id = :id AND receiver_id = :id2)
               OR (sender_id = :id2 AND receiver_id = :id)
            ORDER BY date, hour
            ")
            ->fetchAll([
                'id' => $id,
                'id2' => $id2
            ]);
    }

    public function addMessage(
        int    $receiverId,
        int    $senderId,
        string $message,
        string $date,
        string $hour
    ): bool
    {
        return $this
            ->query("
            INSERT INTO messages (receiver_id, sender_id, message, date, hour)
            VALUES (:receiverId, :sender_id, :message, :date, :hour)
        ")
            ->execute([
                'receiverId' => $receiverId,
                'sender_id' => $senderId,
                'message' => $message,
                'date' => $date,
                'hour' => $hour,
            ]);
    }

    public function updateMessage(
        int $id
    ): bool
    {
        return $this
            ->query("
            UPDATE messages 
            SET is_view = 1
            WHERE id = :id
            ")
            ->execute([
                'id' => $id
            ]);
    }

    public function countToday(): int
    {
        return $this
            ->query("
            SELECT COUNT(*) 
            FROM messages 
            WHERE date = CURDATE()
            ")
            ->fetchColumn();
    }

    public function count(): int
    {
        return $this
            ->query("
            SELECT COUNT(*) 
            FROM messages
            ")
            ->fetchColumn();
    }
}
