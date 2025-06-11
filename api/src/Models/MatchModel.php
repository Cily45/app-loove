<?php

namespace App\Models;

class MatchModel extends BaseModel
{
    public function addMatch(
        $userId0,
        $userId1,
        $date,
        $is_skiped
    ): bool
    {
        return $this
            ->query('
                INSERT INTO `matches`( `user_id_0`, `user_id_1`, `date`, `is_skiped`) 
                VALUES (:userId0, :userId1, :date, :is_skiped);
            ')
            ->execute([
                'userId0' => $userId0,
                'userId1' => $userId1,
                'date' => $date,
                'is_skiped' => $is_skiped,
            ]);
    }

    public function count()
    {
        return $this
            ->query('
                SELECT COUNT(*) AS reciprocal_matches_count
                FROM matches m1
                JOIN matches m2 ON m1.user_id_0 = m2.user_id_1 AND m1.user_id_1 = m2.user_id_0
                WHERE m1.user_id_0 < m1.user_id_1 AND m1.is_skiped = 0 AND m2.is_skiped = 0;
                ')
            ->fetchColumn();
    }

    public function isMatch(
       int $userId0,
       int $userId1,
    )
    {
        return is_array($this
            ->query('
            SELECT * FROM `matches` 
            WHERE (`user_id_0` = :userId0 AND `user_id_1` = :userId1) OR (`user_id_0` = :userId1 AND `user_id_1` = :userId0) AND `is_skiped` = 0')
            ->fetch(
                [
                    'userId0' => $userId0,
                    'userId1' => $userId1,
                ]
            ));
    }
}