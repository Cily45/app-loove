<?php

namespace App\Controllers;

use App\Models\DogGenderModel;

class DogGenderController extends BaseController
{
    public function liste(): bool|string
    {
        if(!$this->verifyToken()){
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }

        try {
            $model = new DogGenderModel();
            $result = $model->all();
            http_response_code(200);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur récuperation des genres canins: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }
}