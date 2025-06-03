<?php

namespace App\Models;

class AdminModel extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        $result = $this
            ->query("SELECT * FROM admin WHERE email = :email")
            ->fetch(['email' => $email]);

        return $result ?: null;
    }
}