<?php

namespace App\Controllers;

use App\Models\ReportReasonModel;

class ReportReasonController extends BaseController
{
    public function liste() {
        $model = new ReportReasonModel();
        return json_encode($model->all(), JSON_PRETTY_PRINT);
    }
}