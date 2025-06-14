<?php

namespace App\Models;

class ReportModel extends BaseModel
{
    public function countReport(
        int $complainantId,
        int $accusedId
    ): int
    {
       $result =  $this
            ->query("
            SELECT * 
            FROM `report`
            WHERE `complainant_id` = :complainantId AND `accused_id` = :accusedId AND `is_solved` = 0
            ")
            ->fetchAll([
                'accusedId' => $accusedId,
                'complainantId' => $complainantId,
            ]);
         return count($result);
    }

    public function createReport(
        int    $id,
        int    $complainantId,
        int    $reason,
        string $date,
    ): bool
    {
        return $this
            ->query("
            INSERT INTO `report`(`complainant_id`, `accused_id`, `reason_id`, `date`) 
            VALUES (:complainantId,:id,:reason,:date)
            ")
            ->execute([
                'complainantId' => $complainantId,
                'id' => $id,
                'reason' => $reason,
                'date' => $date
            ]);
    }

    public function getAll(
        int $quantity,
        int $offset
    ): array
    {
        return $this
            ->query("
            SELECT report.id, report_reason.name AS reason,report.date, report.is_solved
            FROM report
            JOIN report_reason ON report.reason_id = report_reason.id
            ORDER BY report.date
            LIMIT $quantity OFFSET $offset
            ")
            ->fetchAll();
    }

    public function get(
        $id
    ): array
    {
        return $this
            ->query("
            SELECT report.*, report_reason.name AS reason
            FROM report 
            JOIN report_reason ON report.reason_id = report_reason.id
            WHERE report.id = :id;
            ")
            ->fetch([
                'id' => $id
            ]);
    }

    public function update(
        int $id,
    ): bool
    {
        return $this
            ->query("
            UPDATE `report` 
            SET `is_solved`= 1 
            WHERE id = :id;
            ")
            ->execute([
                'id' => $id
            ]);
    }

    public function count()
    {
        return $this
            ->query("
            SELECT COUNT(*) 
            FROM `report`
            ")
            ->fetchColumn();
    }

    public function countUnsolved()
    {
        return $this
            ->query("
            SELECT COUNT(*) 
            FROM `report` 
            WHERE is_solved = 0
            ")
            ->fetchColumn();
    }
}