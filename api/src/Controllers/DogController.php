<?php

namespace App\Controllers;

use App\Models\DogModel;

class DogController extends BaseController
{
    public function get(int $id): bool|array
    {
        if(!$this->verifyToken()){
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        $model = new DogModel();
        return $model->get($id);
    }
}