<?php

namespace App\Controllers;

use App\Models\PriceModel;

class PriceController extends BaseController
{
    public function get(): bool|string
    {
        if(!$this->verifyToken()){
            http_response_code(401);
            return json_encode(['error' => 'Token invalid']);
        }
        $model = new PriceModel();
        return json_encode($model->get(), JSON_PRETTY_PRINT);
    }
}