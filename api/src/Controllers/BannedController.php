<?php

namespace App\Controllers;

use App\Models\BannedModel;
use DateTime;

class BannedController extends BaseController
{

    public function add(): bool|string
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Token invalid']);
        }

        try {
            $data = json_decode(file_get_contents('php://input'));
            $id = $data->id ?? null;
            $time = $data->time ?? null;

            if (!$id || !$time) {
                http_response_code(400);
                return json_encode(['error' => 'Banned date is missing'], JSON_PRETTY_PRINT);
            }

            $model = new BannedModel();
            $user = $model->get($id);
            if (!$user) {
                date_default_timezone_set('Europe/Paris');
                $date_begin = (new DateTime())->format('Y-m-d');
                $date_end = new DateTime();
                $date_end->modify("+$time month");

                $result = $model->create($id, $date_begin, $date_end->format('Y-m-d'));

                http_response_code(200);
                return json_encode(['result' => $result], JSON_PRETTY_PRINT);
            } else {
                date_default_timezone_set('Europe/Paris');
                $date_end = new DateTime($user['end_date']);
                $date_end->modify("+$time month");

                $result = $model->update($id, $date_end);

                http_response_code(200);
                return json_encode(['result' => $result], JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            error_log("Erreur avec le bannissement: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function delete(int $id): bool|string
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Token invalid']);
        }

        try {
            $model = new BannedModel();
            $result = $model->delete($id);
            http_response_code(200);
            return json_encode(['result' => $result], JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            error_log("Erreur avec le débannissement: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }
}