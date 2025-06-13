<?php

namespace App\Controllers;

use Pusher\Pusher;
use Pusher\PushNotifications\PushNotifications;

class AuthPusherController extends BaseController
{
    public function authPusher()
    {
        $id = (int)$this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }

        $options = ['cluster' => 'eu', 'useTLS' => true];
        $pusher = new Pusher($_ENV['PUSHER_KEY'], $_ENV['PUSHER_SECRET'], $_ENV['PUSHER_ID'], $options);

        try {
            $socket_id = $_POST['socket_id'];
            $channel_name = $_POST['channel_name'];

            if (!preg_match('/^private-users-(\d+)-(\d+)$/', $channel_name, $matches) &&
                !preg_match('/^private-user-message-(\d+)$/', $channel_name, $matches) &&
                !preg_match('/^private-user-match-(\d+)$/', $channel_name, $matches)) {
                http_response_code(403);
                return json_encode(['error' => 'Nom de canal invalide']);
            }

            if (str_contains($channel_name, 'users')) {
                $userA = intval($matches[1]);
                $userB = intval($matches[2]);

                if ($id !== $userA && $id !== $userB) {
                    http_response_code(403);
                    return json_encode(['error' => 'Accès refusé au channel']);
                }
            }

            if (str_contains($channel_name, 'message')) {
                $user = intval($matches[1]);

                if ($id !== $user) {
                    http_response_code(403);
                    return json_encode(['error' => 'Accès refusé au channel']);
                }
            }

            if (str_contains($channel_name, 'match')) {
                $user = intval($matches[1]);

                if ($id !== $user) {
                    http_response_code(403);
                    return json_encode(['error' => 'Accès refusé au channel']);
                }
            }

            return $pusher->authorizeChannel($channel_name, $socket_id);
        } catch (Exception $e) {
            error_log("Erreur d'authentification au canal: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }

    }
}