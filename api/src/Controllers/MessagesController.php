<?php

namespace App\Controllers;

use App\Models\MessagesModel;
use App\Models\UserModel;
use App\Services\NotificationService;
use Pusher\Pusher;

class MessagesController extends AuthController
{
    public function messages()
    {
        $id = (int)$this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Token invalid']);
        }

        try {
            $model = new MessagesModel();
            return json_encode($model->allMessageById($id));

        } catch (\Throwable $e) {
            error_log("Erreur récuperation conversation: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function messagesById(int $id0, int $id1): bool|string
    {
        if (!$this->isAdmin()) {
            $userId = (int)$this->getId();

            if (!$userId || $userId !== $id0) {
                http_response_code(401);
                return json_encode(['error' => 'Token Invalid.']);
            }
        }

        try {
            $model = new MessagesModel();
            $result = $model->allMessageByIdById($id0, $id1);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur récuperation conversation: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function addMessage()
    {
        $senderId = $this->getId();
        if (!$senderId) {
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $receiverId = isset($data['receiver_id']) ? (int)$data['receiver_id'] : null;
            $message = isset($data['message']) ? $data['message'] : null;
            date_default_timezone_set('Europe/Paris');
            $date = date('Y-m-d');
            $hour = date("H:i:s");



            if (!$receiverId || !$message) {
                http_response_code(400);
                return json_encode(['error' => 'Champs manquants.']);
            }



            $model = new MessagesModel();
            $result = $model->addMessage($receiverId, $senderId, $message, $date, $hour);

            $options = [
                'cluster' => 'eu',
                'useTLS' => true
            ];

            $pusher = new Pusher(
                $_ENV['PUSHER_KEY'],
                $_ENV['PUSHER_SECRET'],
                $_ENV['PUSHER_ID'],
                $options
            );
            $channel = 'private-users-' . ($senderId > $receiverId ? $senderId .'-'. $receiverId : $receiverId .'-'. $senderId) ;

            $pusher->trigger($channel, 'new-message', [
                'id' => $result,
                'receiver' => $receiverId,
                'sender' => $senderId,
                'message' => $message,
                'date' => $date,
                'hour' => $hour
            ]);

            $channel = 'private-user-message-' .  $receiverId;

            $pusher->trigger($channel, 'new-message', [
                'sender' => $senderId,
            ]);

            $userModel = new UserModel();
            $userReceiver = $userModel->get($receiverId);
            $userSender = $userModel->get($senderId);

            if($userReceiver['message_email'] === 1){
                $mail = new MailController();
                $mail->sendMessage($userSender['firstname'], $userSender['profil_photo'], $userSender['id'], $message, $userReceiver['email']);
            }

            if($userReceiver['message_push'] === 1){
                $notification = new NotificationService();
                $notification->send_notification_new_message($userReceiver['id'], $userSender['firstname'], $userSender['id']);
            }

            http_response_code(201);
            return json_encode(['success' => true], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur récuperation conversation: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }


    }

    public function viewed(int $id)
    {
        if (!$this->verifyToken()) {
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }

        try {
            $model = new MessagesModel();
            $result = json_encode($model->updateMessage($id));
            return JSON_encode($result, JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            error_log("Erreur d'actualisation de vue: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }
}