<?php

namespace App\Controllers;

use App\Models\UserModel;
use DateTime;

class UsersController extends BaseController
{

    public function liste(int $quantity, int $page)
    {
        $quantity = cleanString($quantity);
        $page = cleanString($page);
        $model = new UserModel();
        $res = $model->all($quantity, $page);
        return json_encode($res, JSON_PRETTY_PRINT);
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
        $birthday = DateTime::createFromFormat('Y-m-d', cleanString($data['birthday']));
        $gender = isset($data['gender']) ? (int)cleanString($data['gender']) : null;
        $email = cleanString($data['email']);
        $password = isset($data['password'])?trim($data['password']) : '0000';
        $userModel = new UserModel();

        if (!$firstname || !$lastname || !$birthday || !$email || !$password) {
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
            $email,
            $hashedPassword
        );

        http_response_code(201);
        return json_encode(['success' => true], JSON_PRETTY_PRINT);
    }

    public function profil(string $id)
    {
        try {
            $userId = $this->getId();
            if (!$userId) {
                return null;
            }

            $model = new UserModel();
            return json_encode($model->getProfil($id));

        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
        }
    }

    public function allProfil()
    {
        try {
            $id = $this->getId();
            if (!$id) {
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

    public function allProfilMessage()
    {
        try {
            $id = $this->getId();
            if (!$id) {
                return json_encode(['error' => 'Erreur de token']);
            }
            $model = new UserModel();

            return json_encode($model->getAllProfilMessage($id));

        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur :' . $e->getMessage()]);
        }
    }

    public function matchs()
    {
        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }
        $model = new UserModel();

        return json_encode($model->getAllProfilMatch($id));
    }

    public function updateLocation()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        $location = cleanString($data['location']);
        $model = new UserModel();

        return json_encode($model->updateLocation($id, $location));
    }

    public function banned(int $id)
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }
        $model = new UserModel();
        return json_encode($model->updateBanned($id));
    }

    public function delete(int $id): bool|string
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }


        $model = new UserModel();
        return json_encode($model->delete($id));
    }

    public function profilAdmin(int $id): bool|string
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }
        $model = new UserModel();
        return json_encode($model->getProfilAdmin($id));
    }

    public function updateProfilAdmin(): bool|string
    {
        if(!$this->isAdmin()){
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (isset($data['id']) && is_numeric($data['id'])) ? $data['id'] : null;
        $firstname = cleanString($data['firstname']);
        $lastname = cleanString($data['lastname']);
        $birthday = DateTime::createFromFormat('Y-m-d', cleanString($data['birthday']));
        $email = cleanString($data['email']);
        $userModel = new UserModel();
        if (!$firstname || !$lastname || !$birthday || !$email) {
            http_response_code(400);
            return json_encode(['error' => 'Champs manquants.']);
        }

        http_response_code(201);
        return json_encode($userModel->updateUser(
            $id,
            $firstname,
            $lastname,
            $birthday,
            $email
        ));
    }
}