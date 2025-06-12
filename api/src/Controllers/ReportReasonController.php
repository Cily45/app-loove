<?php

namespace App\Controllers;

use App\Models\ReportReasonModel;

class ReportReasonController extends BaseController
{
    public function liste()
    {
        try {
            $model = new ReportReasonModel();
            $result = $model->all();
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur de récuperation du signalement: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }
}