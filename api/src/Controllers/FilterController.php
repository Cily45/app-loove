<?php

namespace App\Controllers;

use App\Models\FilterModel;
use mysql_xdevapi\Result;

class FilterController extends BaseController
{
    public function get(): bool|string
    {
        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }

        try {
            $model = new FilterModel();
            $result = $model->get($id);
            http_response_code(200);
            return json_encode($result, JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            error_log("Erreur avec la maj d'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function add(): bool|string
    {
        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);

            $genders = isset($data['genders']) && is_array($data['genders']) ? $data['genders'] : null;
            $hobbies = isset($data['hobbies']) && is_array($data['hobbies']) ? $data['hobbies'] : null;
            $dogGender = isset($data['dogGender']) && is_array($data['dogGender']) ? $data['dogGender'] : null;
            $dogSize = isset($data['dogSize']) && is_array($data['dogSize']) ? $data['dogSize'] : null;
            $dogTemperament = isset($data['dogTemperament']) && is_array($data['dogTemperament']) ? $data['dogTemperament'] : null;
            $minAge = isset($data['minAge']) ? cleanString($data['minAge']) : null;
            $maxAge = isset($data['maxAge']) ? cleanString($data['maxAge']) : null;
            $distance = isset($data['distance']) ? cleanString($data['distance']) : null;

            $model = new FilterModel();

            $model->filterReset($id);

            foreach ($genders as $gender) {

                $model->addGender($id, $gender["id"]);
            }

            foreach ($hobbies as $hobby) {
                $model->addHobbies($id, $hobby["id"]);
            }

            foreach ($dogGender as $gender) {
                $model->addDogGender($id, $gender["id"]);
            }

            foreach ($dogSize as $size) {
                $model->addDogSize($id, $size["id"]);
            }

            foreach ($dogTemperament as $temperament) {
                $model->addDogTemperament($id, $temperament["id"]);
            }

            $result = $model->addFilter($id, $minAge, $maxAge, $distance);

            http_response_code(200);
            return json_encode($result, JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            error_log("Erreur avec la maj d'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function reset(): bool
    {
        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }

        try {
            $model = new FilterModel();
            $result = $model->filterReset($id);
            http_response_code(200);
            return json_encode($result, JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            error_log("Erreur avec la maj d'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }
}