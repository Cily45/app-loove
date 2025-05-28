<?php

namespace App\Controllers;

use App\Models\MessagesModel;
use App\utils\AuthService;

class MessagesController extends AuthController
{
    public function messages()
    {
        try {
            $id =(int) $this->getId();
            if(!$id){
                http_response_code(401);
                return json_encode(['error' => 'Token invalid']);
            }

            $model = new MessagesModel();

            return json_encode($model->allMessageById($id));

        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
        }
    }
    public function messagesById($id): bool|string
    {
        if(!$this->verifyToken()){
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }

        try {
            $userId =(int) $this->getId();

            $model = new MessagesModel();
            return json_encode($model->allMessageByIdById($userId, $id));

        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
        }
    }

    public function addMessage()
    {
        if(!$this->verifyToken()){
            http_response_code(401);
            return json_encode(['error' => 'Token Invalid.']);
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $senderId = $this->getId();
        $receiverId= cleanString($data['receiver_id']);
        $message = cleanString($data['message']);
        date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d');
        $hour = date("H:i:s");
        $model = new MessagesModel();

        if ( !$receiverId || !$message) {
            http_response_code(400);
            return json_encode(['error' => 'Champs manquants.']);
        }

        if (!$senderId ) {
            http_response_code(400);
            return json_encode(['error' => 'Action non autorisé']);
        }

        $model->addMessage(
            $receiverId,
            $senderId,
            $message,
            $date,
            $hour
        );

        http_response_code(201);
        return json_encode(['success' => true], JSON_PRETTY_PRINT);

    }

    public function viewed(int $id){
        try {
            $userId =(int) $this->getId();

            $model = new MessagesModel();
            return json_encode($model->updateMessage($userId, $id));

        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
        }
    }
}