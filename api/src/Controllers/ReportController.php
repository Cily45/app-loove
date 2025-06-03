<?php

namespace App\Controllers;

use App\Models\ReportModel;

class ReportController extends BaseController
{
    public function create(): string
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($this->verifyToken()) {
            $id = cleanString($data['id']);
            $complainantId = $this->getId();
            $reason = cleanString($data['reason']);
            date_default_timezone_set('Europe/Paris');
            $date = date('Y-m-d');
            $model = new ReportModel();

            if (!$id || !$reason || !$complainantId) {
                http_response_code(400);
                return json_encode(['error' => 'Champs manquants.']);
            }

            if ($model->getReport($complainantId, $id)) {
                http_response_code(200);
                return json_encode(['error' => 'Signalement déjà créé'], JSON_PRETTY_PRINT);
            }

            $model->createReport(
                $id,
                $complainantId,
                $reason,
                $date
            );

            http_response_code(201);
            return json_encode(['success' => true], JSON_PRETTY_PRINT);
        }
        http_response_code(401);
        return json_encode(['error' => 'Token invalid']);
    }

    public function list(int $quantity, int $page): bool|string
    {
        if(!$this->isAdmin()){
            http_response_code(401);
            return json_encode(['error' => 'Token invalid']);
        }

        $model = new ReportModel();
        return json_encode($model->getAll($quantity, $page));
    }

    public function get(int $id): bool|string{
        if(!$this->isAdmin()){
            http_response_code(401);
            return json_encode(['error' => 'Token invalid']);
        }

        $model = new ReportModel();
        return json_encode($model->get($id));
    }
}