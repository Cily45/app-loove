<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\BannedModel;
use App\Models\UserModel;
use Firebase\JWT\JWT;

class AuthController extends BaseController
{

    public function login()
    {
        $key = $_ENV['JWT_KEY'];
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            return json_encode(['error' => 'Email et mot de passe requis'], JSON_PRETTY_PRINT);
        }

        $email = trim($data['email']);
        $password = trim($data['password']);

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            http_response_code(401);
            return json_encode(['error' => 'Identifiants invalides'], JSON_PRETTY_PRINT);
        }

        if($user['is_verified'] === 0){
            http_response_code(403);
            return json_encode(['error' => 'Utilisateur non verifié'], JSON_PRETTY_PRINT);
        }

        $bannedModel = new BannedModel;
        $banned = $bannedModel->get($user['id']);
        if($banned){
            if($banned['end_date'] < date('Y-m-d')){
                $bannedModel->delete($user['id']);
            }else{
                http_response_code(403);
                return json_encode(['error' => 'Utilisateur banni'], JSON_PRETTY_PRINT);
            }
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
        $response = [
            'message' => 'Connexion réussie',
            'token' => $token,
            'id' => $user['id']
        ];
        http_response_code(200);
        return json_encode($response, JSON_PRETTY_PRINT);
    }
}