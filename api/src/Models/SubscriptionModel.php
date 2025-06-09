<?php

namespace App\Models;

class SubscriptionModel extends BaseModel
{
    public function get(
        int $id
    ): array|bool
    {
        return $this->query("
                SELECT * 
                FROM `subscription` 
                WHERE user_id = :id;
")
            ->fetch(['id' => $id]);
    }

    public function create(
        int    $id,
        string $begin_date,
        string $end_date
    ): bool
    {
        return $this->query("
                INSERT INTO `subscription`(`user_id`, `begin_date`, `end_date`) 
                VALUES (:id ,:begin_date, :end_date);)
                ")
            ->fetch([
                'id' => $id,
                'begin_date' => $begin_date,
                'end_date' => $end_date
            ]);
    }

    public function update(
        int    $id,
        string $end_date
    ): bool
    {
        return $this->query("
                UPDATE `subscription` 
                SET `end_date`= :end_date 
                WHERE user_id = :id;
            ")
            ->fetch([
                'id' => $id,
                'end_date' => $end_date
            ]);
    }

    public function delete(
        int $id
    ): bool
    {
        $this->query("DELETE FROM user_filter_dog_sizes WHERE user_id = :id")->execute(['id' => $id]);
        $this->query("DELETE FROM user_filter_dog_genders WHERE user_id = :id")->execute(['id' => $id]);
        $this->query("DELETE FROM user_filter_dog_temperament WHERE user_id = :id")->execute(['id' => $id]);
        $this->query("DELETE FROM user_filter_hobbies WHERE user_id = :id")->execute(['id' => $id]);

        return $this
            ->query("
                DELETE FROM `subscription` 
                WHERE user_id = :id;
                ")
            ->fetch([
                'id' => $id
            ]);
    }

    public function count(): int
    {
        return $this
            ->query('
                SELECT COUNT(DISTINCT user_id) AS currently_subscribed
                FROM subscription
                WHERE end_date > NOW();
                ')
            ->fetchColumn();
    }
}