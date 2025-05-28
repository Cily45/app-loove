<?php

namespace App\Models;

class PriceModel extends BaseModel
{
    public function get(): array
    {
        return $this
            ->query("SELECT `price`, `quantity` FROM `price`")
            ->fetchAll();
    }

}