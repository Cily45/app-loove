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

    public function update(): bool|string
    {
        if(!$this->isAdmin()){
            http_response_code(401);
            return json_encode(['error' => 'Token invalid']);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !is_array($data)) {
            http_response_code(400);
            return json_encode(['error' => 'Invalid data format']);
        }

        $model = new PriceModel();

        try {
            foreach($data as $item) {
                if (!isset($item['id']) || !isset($item['price'])) {
                    http_response_code(400);
                    return json_encode(['error' => 'Missing id or price in data']);
                }

                $model->update((int)$item['id'], (float)$item['price']);
            }

            return json_encode(['success' => 'Prices updated successfully']);

        } catch (\Exception $e) {
            http_response_code(500);
            return json_encode(['error' => $e->getMessage()]);
        }
    }
}