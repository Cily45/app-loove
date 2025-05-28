<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\UserModel;
use Firebase\JWT\JWT;

class AuthController extends BaseController
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

        $email = trim($data['email']);
        $password = trim($data['password']);

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {

            $response = new Response(401, json_encode(['error' => 'Identifiants invalides']));
            header("Content-Type: application/json");
            return $response->getBody();
        }

        $payload = [
            'iss' => 'pawfectmatch.local',
            'aud' => 'pawfectmatch.local',
            'iat' => time(),
            'exp' => time() + 36000,
            'sub' => $user['id'],
            'email' => $user['email']
        ];

        $token = JWT::encode($payload, $key, 'HS256');
        $response = new Response(200, json_encode([
            'message' => 'Connexion réussie',
            'token' => $token,
            'id' => $user['id']
        ]));

        header("Content-Type: application/json");
        return $response->getBody();
    }



}
