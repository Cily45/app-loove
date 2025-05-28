<?php

namespace App\Controllers;

use App\Models\HobbiesModel;

class HobbiesController extends BaseController
{
    public function liste()
    {
        $model = new HobbiesModel();
        return json_encode($model->all(), JSON_PRETTY_PRINT);
    }

    public function get()
    {
        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);

        }
        $model = new HobbiesModel();
        http_response_code(200);
        return json_encode($model->get($id), JSON_PRETTY_PRINT);

    }
}