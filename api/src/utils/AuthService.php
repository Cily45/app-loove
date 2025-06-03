<?php

namespace App\utils;

use Exception;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AuthService
{
    public function authenticateUser(string $jwt): ?int
    {
        try {
            $decoded = JWT::decode($jwt, new Key( $_ENV['JWT_KEY'], 'HS256'));
            return $decoded->sub ?? null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function isAuthenticateAdmin(string $jwt): bool
    {
        try {
            $decoded = JWT::decode($jwt, new Key( $_ENV['JWT_KEY'], 'HS256'));
            return $decoded->admin ?? false;
        } catch (Exception $e) {
            return false;
        }
    }
}