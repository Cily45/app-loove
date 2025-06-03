<?php

namespace App\Controllers;

use App\Models\DogModel;

class DogController extends BaseController
{
    public function get(int $id): bool|array
    {
        if (!$this->verifyToken()) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        $model = new DogModel();
        http_response_code(200);

        return $model->get($id);
    }

    public function create(): bool|array
    {
        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        $model = new DogModel();
        $model->delete($id);

        $data = json_decode(file_get_contents('php://input'), true);
        $dogs = $data['dogs'];
        foreach ($dogs as $dog) {
            $name = cleanString($dog['name']);
            $birthday = cleanString($dog['birthday']);
            $gender = (int)cleanString($dog['gender']);
            $size = (int)cleanString($dog['size']);
            $temperament = (int)cleanString($dog['temperament']);
            if (!$name || !$birthday) {
                http_response_code(400);
                return json_encode(['error' => 'Champs manquants.']);
            }
            $model->add($id, $name, $birthday, $gender, $size, $temperament);
        }
        http_response_code(200);
        return json_encode(true, JSON_PRETTY_PRINT);
    }
}