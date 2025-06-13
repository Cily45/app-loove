<?php

namespace App\Services;

use Pusher\PushNotifications\PushNotifications;

class NotificationService {
    private $beamsClient;

    public function __construct(){
        $this->beamsClient= new PushNotifications(array(
            "instanceId" => $_ENV['BEAMS_ID'],
            "secretKey" => $_ENV['BEAMS_KEY'],
        ));
    }

    public function send_notification_new_match($send_to, $name, $id) {
        $this->beamsClient->publishToInterests(
            ["$send_to"],
            [
                "fcm" => [
                    "notification" => [
                        "title" => "Nouveau Match",
                        "body" => "Vous avez matcher avec $name",
                        "icon" => "https://pawfectmatch.dev/uploads/logo-entier.png",
                        "deep_link" => "https://pawfectmatch.dev:4200/messagerie/discussion/" . $id,
                    ]
                ],
                "web" => [
                    "notification" => [
                        "title" => "Nouveau Match",
                        "body" => "Vous avez matcher avec $name",
                        "icon" => "https://pawfectmatch.dev/uploads/logo-entier.png",
                        "deep_link" => "https://pawfectmatch.dev:4200/messagerie/discussion/" . $id,
                    ]
                ]
            ]
        );
    }

    public function send_notification_new_message($send_to, $name, $id) {
        $this->beamsClient->publishToInterests(
            ["$send_to"],
            [
                "fcm" => [
                    "notification" => [
                        "title" => "Nouveau Message",
                        "body" => "Vous avez reçu un nouveau message de $name",
                        "icon" => "https://pawfectmatch.dev/uploads/logo-entier.png",
                        "deep_link" => "https://pawfectmatch.dev:4200/messagerie/discussion/" . $id,
                    ]
                ],
                "web" => [
                    "notification" => [
                        "title" => "Nouveau message",
                        "body" => "Vous avez reçu un nouveau message de $name",
                        "icon" => "https://pawfectmatch.dev/uploads/logo-entier.png",
                        "deep_link" => "https://pawfectmatch.dev:4200/messagerie/discussion/" . $id,
                    ]
                ]
            ]
        );
    }
}