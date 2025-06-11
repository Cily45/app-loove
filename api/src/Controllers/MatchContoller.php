<?php

namespace App\Controllers;

use App\Models\MatchModel;
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

                $res = $model->isMatch($user0, $user1);

                if ($res) {
                    $channel0 = 'private-user-match-' . $user0;
                    $channel1 = 'private-user-match-' . $user1;

                    $pusher->trigger($channel0, 'new-match', [
                        'match' => $user1,
                    ]);

                    $pusher->trigger($channel1, 'new-match', [
                        'match' => $user0,
                    ]);
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