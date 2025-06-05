<?php

namespace App\Models;

use DateTime;

class BannedModel extends BaseModel
{
    public function create(
        int      $id,
        string $date_begin,
        string $date_end,
    ): bool
    {
        return $this
            ->query("
        INSERT INTO `banned`(`user_id`, `end_date`, `begin_date`) 
        VALUES (:id,:date_end, :date_begin)
        ")
            ->execute([
                    "id" => $id,
                    "date_begin" => $date_begin,
                    "date_end" => $date_end]
            );
    }

    public function update(
        int      $id,
        string $date_end,
    ): bool
    {
        return $this
            ->query("
        UPDATE `banned` 
        SET `end_date`= :date_end 
        WHERE user_id = :id
        ")
            ->execute([
                    "id" => $id,
                    "date_end" => $date_end
                ]
            );
    }

    public function delete(
        int $id,
    ): bool
    {
        return $this
            ->query("
        DELETE FROM `banned`
        WHERE user_id = :id
        ")
            ->execute([
                    "id" => $id,
                ]
            );
    }

    public function get(
        int $id,
    )
    {
        return $this
            ->query("
        SELECT *
        FROM `banned`
        WHERE user_id = :id
        ")
            ->fetch([
                    "id" => $id,
                ]
            );
    }
}