<?php

namespace App\Models;

class GenderModel extends BaseModel
{
    public function all(): array
    {
        return $this
            ->query("
                SELECT * 
                FROM gender
                ")
            ->fetchAll();
    }

    public function get(
        int $id
    ): array
    {
        return $this
            ->query("
                    SELECT gender.id, gender.name,
                    CASE WHEN user_gender_preferences.user_id IS NOT NULL THEN TRUE ELSE FALSE END AS selected
                    FROM gender
                    LEFT JOIN user_gender_preferences ON user_gender_preferences.gender_id = gender.id AND user_gender_preferences.user_id = :id
                    ORDER BY gender.id;
                ")
            ->fetchAll([
                'id' => $id
            ]);
    }

    public function add(
        int $genderId,
        int $userId
    ): bool
    {
        return $this
            ->query("
                  INSERT INTO `user_gender_preferences`(`user_id`, `gender_id`)
                    VALUES(:id, :genderId)
                ")
            ->execute([
                'id' => $userId,
                'genderId' => $genderId
            ]);
    }

    public function delete(
        int $id
    ): bool
    {
        return $this
            ->query("
                    DELETE
                    FROM `user_gender_preferences`
                    WHERE user_id = :id
                ")
            ->execute([
                ':id' => $id,
            ]);
    }
}
