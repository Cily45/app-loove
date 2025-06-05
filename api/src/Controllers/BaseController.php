<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\utils\AuthService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

abstract class BaseController
{

    protected Request $request;

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    protected function getRequest(): Request
    {
        return $this->request;
    }

    protected function verifyToken(): array|string
    {
        $headers = getallheaders();
        if (!isset($headers['X-Access-Token'])) {
            return false;
        }

        $authHeader = $headers['X-Access-Token'];
        if (!str_starts_with($authHeader, 'Bearer ')) {
            return false;
        }

        $jwt = substr($authHeader, 7);

        try {
            JWT::decode($jwt, new Key($_ENV['JWT_KEY'], 'HS256'));
            return true;
        } catch (\Exception $e) {
            return false;
        }

    }

    protected function getId(): string|bool
    {
        $headers = getallheaders();
        if (!isset($headers['X-Access-Token'])) {
            return false;
        }

        $token = str_replace('Bearer ', '', $headers['X-Access-Token']);

        $authService = new AuthService();
        $userId = $authService->authenticateUser($token);
        if (!$userId) {
            return false;
        }

        return $userId;
    }

    protected function isAdmin(): string|bool
    {
        $headers = getallheaders();
        if (!isset($headers['X-Access-Token'])) {
            return false;
        }

        $token = str_replace('Bearer ', '', $headers['X-Access-Token']);

        $authService = new AuthService();
        $userId = $authService->isAuthenticateAdmin($token);
        if (!$userId) {
            return false;
        }

        return true;
    }

}
