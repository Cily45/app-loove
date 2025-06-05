<?php

namespace App\Controllers;

use App\Models\GenderModel;

class GenderController extends BaseController
{
    public function liste(): bool|string
    {
        try {
            $model = new GenderModel();
            $result = $model->all();
            http_response_code(200);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur récuperation des genres: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function get(int $id): bool|string
    {
        if(!$this->verifyToken()){
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }

        try {
            $model = new GenderModel();
            $result = $model->get($id);
            http_response_code(200);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur récuperation des genres: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function add(int $id): bool|string
    {
        try {
            $model = new GenderModel();
            $result = $model->delete($id);

            $data = json_decode(file_get_contents('php://input'), true);
            var_dump($data);
            $genders = $data['gender'];
//            foreach ($genders as $gender) {
//                $name = cleanString($dog['name']);
//                $birthday = cleanString($dog['birthday']);
//                $gender = (int)cleanString($dog['gender']);
//                if (!$name || !$birthday) {
//                    http_response_code(400);
//                    return json_encode(['error' => 'Champs manquants.']);
//                }
//                $model->add($id, $name, $birthday, $gender, $size, $temperament);

            http_response_code(200);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur récuperation des genres: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }
}
