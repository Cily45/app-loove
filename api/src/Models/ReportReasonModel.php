<?php

namespace App\Models;

class ReportReasonModel extends BaseModel
{
    public function all(): array
    {
        return $this
            ->query("SELECT * FROM report_reason")
            ->fetchAll();
    }

}