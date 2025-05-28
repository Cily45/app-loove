<?php

namespace App\Models;

class ReportModel extends BaseModel
{
    public function getReport(int $complainantId, int $accusedId): array
    {
        $stmt = $this->query("
        SELECT * FROM `report`
        WHERE `complainant_id` = :complainantId
          AND `accused_id` = :accusedId
          AND `is_solved` = 0
    ");

        $stmt->execute([
            ':accusedId' => $accusedId,
            ':complainantId' => $complainantId,
        ]);

        return $stmt->fetchAll();
    }

    public function createReport(
        int    $id,
        int    $complainantId,
        int    $reason,
        string $date,

    ): void
    {
        $stmt = $this->query("
       INSERT INTO `report`(`complainant_id`, `accused_id`, `reason_id`, `date`) VALUES (:complainantId,:id,:reason,:date)
    ");

        $stmt->execute([
            ':complainantId' => $complainantId,
            ':id' => $id,
            ':reason' => $reason,
            ':date' => $date
        ]);
    }
}