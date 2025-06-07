<?php

namespace App\Models;

class HobbiesModel extends BaseModel
{
    public function all(): array
    {
        return $this
            ->query("
                SELECT * 
                FROM hobbies")
            ->fetchAll();
    }

    public function get(
        int $id
    ): array
    {
        return $this
            ->query("
                SELECT hobbies.id, hobbies.name, hobbies.icon,
                CASE WHEN user_hobbies.user_id IS NOT NULL THEN TRUE ELSE FALSE END AS selected
                FROM hobbies
                LEFT JOIN user_hobbies ON user_hobbies.hobbies_id = hobbies.id AND user_hobbies.user_id = :id
                ORDER BY hobbies.id;
")
            ->fetchAll([
                'id' => $id
            ]);
    }

    public function delete(
        int $id
    ): bool
    {
        return $this
            ->query("
                    DELETE
                    FROM `user_hobbies`
                    WHERE user_id = :id
                ")
            ->execute([
                'id' => $id,
            ]);
    }

    public function add(
        int $userId,
        int $hobbyId,
    ): bool
    {
        return $this
            ->query("
                  INSERT INTO `user_hobbies`(`user_id`, `hobbies_id`)
                    VALUES(:id, :hobbyId)
                ")
            ->execute([
                'id' => $userId,
                'hobbyId' => $hobbyId
            ]);
    }
}