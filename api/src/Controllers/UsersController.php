<?php

namespace App\Controllers;

use App\Models\UserModel;

class UsersController extends BaseController
{

    public function liste()
    {
        $model = new UserModel();
        return json_encode($model->all(), JSON_PRETTY_PRINT);
    }

    public function user(string $id)
    {
        $model = new UserModel();
        return json_encode($model->get($id), JSON_PRETTY_PRINT);
    }

    public function isEmailUsed(): bool|string
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email'])) {
            http_response_code(400);
            return json_encode(['error' => 'Email requis']);
        }

        $email = trim($data['email']);

        $userModel = new UserModel();
        $response = $userModel->findByEmail($email);

        return json_encode(['used' => (bool)$response], JSON_PRETTY_PRINT);

    }

    public function create(): string
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $firstname = cleanString($data['firstname']);
        $lastname = cleanString($data['lastname']);
        $birthday = cleanString($data['birthday']);
        $gender = (int) cleanString($data['gender']);
        $sexualOrientation = (int) cleanString($data['sexualOrientation']);
        $email = cleanString($data['email']);
        $password = trim($data['password']);
        $userModel = new UserModel();

        if (!$firstname || !$lastname || !$birthday || !$gender || !$sexualOrientation || !$email || !$password) {
            http_response_code(400);
            return json_encode(['error' => 'Champs manquants.']);
        }

        if ($userModel->findByEmail($email)) {
            http_response_code(409);
            return json_encode(['error' => 'Email déjà utilisé.']);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userModel->createUser(
            $firstname,
            $lastname,
            $birthday,
            $gender,
            $sexualOrientation,
            $email,
            $hashedPassword
        );

        http_response_code(201);
        return json_encode(['success' => true], JSON_PRETTY_PRINT);
    }

    public function profil(string $id){
        try {
            $userId = $this->getId();
            if(!$userId){
                return null;
            }

            $model = new UserModel();
            return json_encode($model->getProfil($id));

        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
        }
    }

    public function allProfil(){
        try {
            $id = $this->getId();
            if(!$id){
                http_response_code(401);
                return json_encode(['error' => 'Erreur de token']);
            }
            $model = new UserModel();
            $res = json_encode($model->getAllProfil($id));
            return $res;

        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur :' . $e->getMessage()]);
        }
    }

    public function allProfilMessage(){
        try {
            $id = $this->getId();
            if(!$id){
                return json_encode(['error' => 'Erreur de token']);
            }
            $model = new UserModel();

            return json_encode($model->getAllProfilMessage($id));

        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur :' . $e->getMessage()]);
        }
    }

    public function matchs(){
        $id = $this->getId();
        if(!$id){
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }
        $model = new UserModel();

        return json_encode($model->getAllProfilMatch($id));
    }

    public function updateLocation(){
        $data = json_decode(file_get_contents('php://input'), true);

        $id = $this->getId();
        if(!$id){
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        $location = cleanString($data['location']);
        $model = new UserModel();

        return json_encode($model->updateLocation($id, $location));
    }
}