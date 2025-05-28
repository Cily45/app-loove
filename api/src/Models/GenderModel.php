<?php
namespace App\Models;

class GenderModel extends BaseModel
{
    public function all(): array
    {
        return $this
            ->query("SELECT * FROM gender")
            ->fetchAll();
    }
}
