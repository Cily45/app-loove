<?php

namespace App\Controllers;

use App\Models\SubscriptionModel;
use DateTime;

class SubscriptionController extends BaseController
{
    public function isActif()
    {
        $id = $this->getId();

        if(!$id){
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        $model = new SubscriptionModel();
        $result = $model->get($id);
        date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d');

        http_response_code(200);
        if (empty($result)) {
            return json_encode(['abonnement' => false]);
        }else if($date > $result['end_date'] || $date < $result['begin_date']){
            $model->delete($id);
            return json_encode(['abonnement' => false]);
        }

        return json_encode(['abonnement' => true]);
    }

    public function info()
    {
        $id = $this->getId();

        if(!$id){
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        $model = new SubscriptionModel();
        $result = $model->get($id);

        http_response_code(200);

        return json_encode($result);
    }

    public function subcription(): bool|string
    {
        $id = $this->getId();
        $isActif = json_decode($this->isActif())->abonnement;
        $data = json_decode(file_get_contents('php://input'), true);
        $time = cleanString($data);

        if(!$id){
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        if($isActif){
            $this->update($id, $time);
        }else {
            $model = new SubscriptionModel();
            date_default_timezone_set('Europe/Paris');
            $date_begin = date('Y-m-d');
            $date_end = new DateTime();
            $date_end->modify("+$time month");

            $date_end = $date_end->format('Y-m-d');
            $model->create($id, $date_begin, $date_end);

        }

        http_response_code(200);
        return json_encode(true);
    }
    public function update(int $id, int $time)
    {
        $model = new SubscriptionModel();
        $info = $model->get($id);
        date_default_timezone_set('Europe/Paris');
        $date_end = new DateTime($info['end_date']);
        $date_end->modify("+$time month");
        $date_end = $date_end->format('Y-m-d');
        return $model->update($id, $date_end);
    }
}