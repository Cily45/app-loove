<?php

namespace App\Controllers;

use App\Models\FilterModel;

class FilterController extends BaseController
{
    public function delete(): bool|string
    {
        if (!$this->verifyToken()) {
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }

        try {
            $id = (int)$this->getId();

            $model = new FilterModel();
            return json_encode($model->delete($id));

        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
        }
    }
}