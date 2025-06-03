<?php

namespace App\Models;

class PriceModel extends BaseModel
{
    public function get(): array
    {
        return $this
            ->query("SELECT * FROM `price`")
            ->fetchAll();
    }

    public function update(int $id, float $price): bool
    {
        return $this->query("UPDATE `price` SET `price` = :price WHERE `id` = :id")
            ->execute([
                ':id' => $id,
                ':price' => $price,
            ]);
    }

}