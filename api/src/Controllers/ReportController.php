<?php

namespace App\Controllers;

use App\Models\ReportModel;

class ReportController extends BaseController
{
    public function create(): string
    {
        $complainantId = $this->getId();
        if (!$complainantId) {
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = isset($data['id']) ? (int)$data['id'] : null;
            $reason = isset($data['reason']) ? (int)$data['reason'] : null;
            date_default_timezone_set('Europe/Paris');
            $date = date('Y-m-d');
            $model = new ReportModel();

            if (!$id || !$reason) {
                http_response_code(400);
                return json_encode(['error' => 'Champs manquants.']);
            }

            $countReport = $model->countReport((int)$complainantId, $id);

            if ($countReport != 0) {
                http_response_code(200);
                return json_encode(['error' => 'Signalement déjà créé'], JSON_PRETTY_PRINT);
            }

            $res = $model->createReport($id, $complainantId, $reason, $date);

            http_response_code(201);
            return json_encode(['success' => $res], JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            error_log("Erreur création de signalement: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function list(int $quantity, int $page): bool|string
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Token invalid']);
        }

        try {
            $model = new ReportModel();
            $offset = ($page - 1) * $quantity;
            $result = $model->getAll($quantity, $offset);
            http_response_code(200);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur récuperation des signalements: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function get(int $id): bool|string
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Token invalid']);
        }

        try {
            $model = new ReportModel();
            $result = $model->get($id);
            return json_encode($result, JSON_PRETTY_PRINT);
        }
        catch (\Exception $e) {
            error_log("Erreur récuperation du signalement: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function update(): bool|string
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Token invalid']);
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = isset($data) ? (int)$data : null;
            if (!$id){
                http_response_code(400);
                return json_encode(['error' => 'Champs manquants.']);
            }
            $model = new ReportModel();
            $result = $model->update($id);
            return json_encode($result, JSON_PRETTY_PRINT);
        }
        catch (\Exception $e) {
            error_log("Erreur màj du signalement: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function count(): int|string
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Token invalid']);
        }

        try {
            $model = new ReportModel();
            $result = $model->count();
            return json_encode($result, JSON_PRETTY_PRINT);
        }
        catch (\Exception $e) {
            error_log("Erreur récuperation du signalement: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function warning(){
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Token invalid']);
        }
    }
}