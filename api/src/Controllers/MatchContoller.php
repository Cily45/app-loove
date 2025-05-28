<?php

namespace App\Controllers;

use App\Models\MatchModel;

class MatchContoller extends BaseController
{
    public function addMatch()
    {
        if(!$this->verifyToken()){
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $user0 = (int) $this->getId();

        if(!$user0){
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }

        $user1 = (int) cleanString($data['userId1']);
        date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d');
        $is_skiped = cleanString($data['is_skiped']) ? 1 : 0;

        $model = new MatchModel();

        if (!$user1 || !$user0) {
            http_response_code(400);
            return json_encode(['error' => 'Champs manquants.']);
        }

        $model->addMatch($user0, $user1, $date, $is_skiped);


        http_response_code(201);
        return json_encode(['success' => true], JSON_PRETTY_PRINT);

    }
}