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

    public function getAll($quantity, $page): array
    {
        $offset = ($page - 1) * $quantity;

        return $this->query("
SELECT 
  report.id,
report_reason.name AS reason,
  report.date,
  report.is_solved
FROM report
JOIN report_reason ON report.reason_id = report_reason.id
ORDER BY report.date
        LIMIT $quantity OFFSET $offset;
        ")
            ->fetchAll();
    }

    public function get($id){
        return $this->query("
SELECT 
  report.*, 
  report_reason.name AS reason
FROM report 
JOIN report_reason ON report.reason_id = report_reason.id
WHERE report.id = :id;
")
            ->fetch([
                'id' => $id
            ]);
    }
    public function count(){
        return $this->query("SELECT COUNT(*) FROM `report`")
            ->fetchColumn();
    }
    public function countUnsolved(){
        return $this->query("SELECT COUNT(*) FROM `report` WHERE is_solved = 0")
            ->fetchColumn();
    }

}