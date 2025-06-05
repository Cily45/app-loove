<?php

namespace App\Models;

class FilterModel extends BaseModel
{
    public function get(
        int $id
    ): array
    {
        return $this
            ->query("
                    SELECT * 
                    FROM filter 
                    WHERE user_id = :id
                    ")
            ->fetchAll();
    }


    public function create(
        int $id,
        string $type,
        string $info)
    : bool
    {
        return $this
            ->query("
                    UPDATE `filter` 
                    SET `user_id`= :id,`type`= :type,`info`= :info
                    ")
            ->execute([
                'id' => $id,
                'type' => $type,
                'info' => $info
            ]);
    }

    public function delete(
        int $id
    ): bool
    {
        return $this
            ->query("
                    DELETE FROM `filter` 
                    WHERE user_id = :id
                    ")
            ->execute(['id' => $id]);
    }

}