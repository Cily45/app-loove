<?php

namespace App\Models;

class DogTemperamentModel extends BaseModel
{
    public function all(): array
    {
        return $this
            ->query("
                SELECT * 
                FROM dog_temperaments
                ")
            ->fetchAll();
    }
}