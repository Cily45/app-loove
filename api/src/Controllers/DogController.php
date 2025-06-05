<?php

namespace App\Controllers;

use App\Models\DogModel;
use PHPMailer\PHPMailer\Exception;

class DogController extends BaseController
{
    public function getAll(int $id)
    {
        if (!$this->verifyToken()) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $model = new DogModel();
            $result = $model->get($id);
            http_response_code(200);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la création de chien: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function create(): bool|array
    {
        $id = $this->getId();

        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $model = new DogModel();
            $model->delete($id);

            $data = json_decode(file_get_contents('php://input'), true);
            $dogs = isset($data['dogs']) && is_array($data['dogs']) ? $data['dogs'] : null;

            if(!$dogs){
                http_response_code(400);
                return json_encode(['error' => 'Champs manquants.']);
            }
            foreach ($dogs as $dog) {
                $name = isset($dog['name']) ? cleanString($dog['name']) : null;
                $birthday = isset($dog['birthday']) ? cleanString($dog['birthday']) : null;
                $gender = isset($dog['gender']) ? (int)$dog['gender'] : null;
                $size = isset($dog['size']) ? (int)$dog['size'] : null;
                $temperament = isset($dog['temperament']) ? (int)$dog['temperament'] : null;

                if (!$name || !$birthday || !$gender || !$size || !$temperament) {
                    http_response_code(400);
                    return json_encode(['error' => 'Champs manquants.']);
                }
                $result = $model->add($id, $name, $birthday, $gender, $size, $temperament);

                if (!$result) {
                    http_response_code(400);
                    return json_encode(['error' => 'Erreur avec la basse de donnée']);
                }
            }

            http_response_code(200);
            return json_encode(true, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la création de chien: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }
}