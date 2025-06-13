<?php

namespace App\Controllers;

use App\Models\MatchModel;
use App\Models\UserModel;
use App\Services\NotificationService;
use Pusher\Pusher;

class MatchContoller extends BaseController
{
    public function addMatch()
    {
        $user0 = (int)$this->getId();

        if (!$user0) {
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $user1 = (int)$data['userId1'];
            date_default_timezone_set('Europe/Paris');
            $date = date('Y-m-d');

            $is_skiped = cleanString($data['is_skiped']) ? 1 : 0;

            $model = new MatchModel();

            if (!$user1 || !$user0) {
                http_response_code(400);
                return json_encode(['error' => 'Champs manquants.']);
            }

            $model->addMatch($user0, $user1, $date, $is_skiped);

            if ($is_skiped === 0) {
                $res = $model->isMatch($user0, $user1);

                if ($res) {
                    $mail = new MailController();
                    $userModel = new UserModel();
                    $user0 = $userModel->get($user0);
                    $user1 = $userModel->get($user1);
                    $notification = new NotificationService();
                    if($user0['match_email'] === 1){
                        $mail->sendMatch($user1['firstname'], $user1['profil_photo'], $user1['id'], $user0['email']);
                    }

                    if($user0['match_push'] === 1){
                        $notification->send_notification_new_match($user0['id'], $user1['firstname'], $user1['id']);
                    }

                    if($user1['match_email'] === 1){
                         $mail->sendMatch($user0['firstname'], $user0['profil_photo'], $user0['id'], $user1['email']);
                    }

                    if($user1['match_push'] === 1){
                        $notification->send_notification_new_match($user1['id'], $user0['firstname'], $user0['id']);
                    }
                }
            }

            http_response_code(201);
            return json_encode(['success' => true], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            error_log("Erreur de match: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }

    }
}