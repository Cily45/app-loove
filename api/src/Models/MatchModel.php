<?php

namespace App\Models;

class MatchModel extends BaseModel
{
    public function addMatch(
        $userId0,
        $userId1,
        $date,
        $is_skiped
    ): void
    {
        $stmt = $this->query("
            INSERT INTO `matches`( `user_id_0`, `user_id_1`, `date`, `is_skiped`) 
            VALUES (:userId0, :userId1, :date, :is_skiped);
");

        $stmt->execute([
            'userId0' => $userId0,
            'userId1' => $userId1,
            'date' => $date,
            'is_skiped' => $is_skiped,
        ]);
    }

}