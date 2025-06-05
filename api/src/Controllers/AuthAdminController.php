<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\AdminModel;
use Firebase\JWT\JWT;

class AuthAdminController extends BaseController
{

    public function login()
    {
        $key = $_ENV['JWT_KEY'];
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['password'])) {
            $response = new Response(400, json_encode(['error' => 'Email et mot de passe requis']));
            header("Content-Type: application/json");
            return $response->getBody();
        }

        $email = cleanString($data['email']);
        $password = trim($data['password']);

        $model = new AdminModel();
        $admin = $model->findByEmail($email);


        $validPassword = $admin && password_verify($password, $admin['password']);
        password_verify($password, '$2y$10$dummy.hash.to.prevent.timing.attacks');

        if (!$admin || !$validPassword) {
            $response = new Response(401, json_encode(['error' => 'Identifiants invalides']));
            header("Content-Type: application/json");
            return $response->getBody();
        }

        $payload = [
            'iss' => 'pawfectmatch.local',
            'aud' => 'pawfectmatch.local',
            'iat' => time(),
            'exp' => time() + 36000,
            'sub' => $admin['id'],
            'email' => $admin['email'],
            'admin' => true,
        ];

        $token = JWT::encode($payload, $key, 'HS256');
        $response = new Response(200, json_encode([
            'message' => 'Connexion réussie',
            'token' => $token,
            'admin' => true,
            'id' => $admin['id']
        ]));

        header("Content-Type: application/json");
        return $response->getBody();
    }
}
