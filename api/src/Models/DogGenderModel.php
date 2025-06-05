<?php

namespace App\Models;

class DogGenderModel extends BaseModel
{
    public function all(): array
    {
        return $this
            ->query("
                SELECT * 
                FROM dog_genders
                ")
            ->fetchAll();
    }
}