<?php

namespace App\Controllers;

use App\Models\InflowModel;
use App\Models\SubscriptionModel;
use DateTime;

class SubscriptionController extends BaseController
{
    public function isActif()
    {
        $id = $this->getId();

        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $model = new SubscriptionModel();
            $result = $model->get($id);
            date_default_timezone_set('Europe/Paris');
            $date = date('Y-m-d');

            http_response_code(200);

            if (empty($result) || $date > $result['end_date'] || $date < $result['begin_date']) {
                if (!empty($result)) {
                    $model->delete($id);
                }
                return json_encode( false);
            }

            return json_encode( true);
        } catch (\Exception $e) {
            error_log("Erreur avec la récupération d'information sur l'abonnement: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur'], JSON_PRETTY_PRINT);
        }
    }

    public function info()
    {
        $id = $this->getId();

        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $model = new SubscriptionModel();
            $result = $model->get($id);

            http_response_code(200);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la récupération d'information sur l'abonnement: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur'], JSON_PRETTY_PRINT);
        }
    }

    public function subcription(): bool|string
    {
        $id = $this->getId();

        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $isActif = $this->isActif();
            $data = json_decode(file_get_contents('php://input'), true);
            $time = isset($data['quantity']) ? (int)$data['quantity'] : null ;
            $price = isset($data['price']) ? (int)$data['price'] : null ;

            if(!$time){
                http_response_code(400);
                return json_encode(['error' => 'Champ temps manquant']);
            }

            (new InflowModel())->add($price);

            if ($isActif === "true") {
                $this->update($id, $time);
            } else {
                $model = new SubscriptionModel();
                date_default_timezone_set('Europe/Paris');
                $date_begin = date('Y-m-d');
                $date_end = new DateTime();
                $date_end->modify("+$time month");
                $date_end = $date_end->format('Y-m-d');
                $result = $model->create($id, $date_begin, $date_end);

            }

            http_response_code(200);
            return json_encode(true);
        } catch (\Exception $e) {
            error_log("Erreur avec l'aabonnement: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur'], JSON_PRETTY_PRINT);
        }
    }

    public function update($id, $time): bool|string
    {
        if (!$this->verifyToken()) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            if (!$time || !$id) {
                http_response_code(400);
                return json_encode(['error' => 'Champs manquants.']);
            }

            $model = new SubscriptionModel();
            $info = $model->get($id);

            date_default_timezone_set('Europe/Paris');
            $date_end = new DateTime($info['end_date']);
            $date_end->modify("+$time month");
            $date_end = $date_end->format('Y-m-d');
            $result = $model->update($id, $date_end);
            return json_encode(['abonnement' => $result], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la màj de l'aabonnement: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur'], JSON_PRETTY_PRINT);
        }
    }
}