<?php

namespace App\Models;

class SubscriptionModel extends BaseModel
{
    public function get(int $id)
    {
        return $this->query("SELECT * FROM `subscription` WHERE user_id = :id;")
            ->fetch(['id' => $id]);
    }

    public function create(int $id, string $begin_date, string $end_date)
    {
        return $this->query("INSERT INTO `subscription`(`user_id`, `begin_date`, `end_date`) VALUES (:id ,:begin_date, :end_date);)")
            ->fetch([
                'id' => $id,
                'begin_date' => $begin_date,
                'end_date' => $end_date
            ]);
    }

    public function update(int $id, string $end_date)
    {
        return $this->query("UPDATE `subscription` SET `end_date`= :end_date WHERE user_id = :id;")
            ->fetch(['id' => $id, 'end_date' => $end_date]);
    }

    public function delete(int $id)
    {
        return $this->query("DELETE FROM `subscription` WHERE user_id = :id;")
            ->fetch(['id' => $id]);
    }
}