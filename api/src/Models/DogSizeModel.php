<?php

namespace App\Models;

class DogSizeModel extends BaseModel
{
    public function all(): array
    {
        return $this
            ->query("
                SELECT * 
                FROM dog_sizes
                ")
            ->fetchAll();
    }
}