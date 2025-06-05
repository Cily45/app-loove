<?php

namespace App\Models;

class AdminModel extends BaseModel
{
    public function findByEmail(
        string $email
    ): array
    {
        return $this
            ->query("
                SELECT * 
                FROM admin 
                WHERE email = :email
                ")
            ->fetch(['email' => $email]);
    }
}