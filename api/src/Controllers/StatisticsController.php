<?php

namespace App\Controllers;

use App\Models\MatchModel;
use App\Models\MessagesModel;
use App\Models\ReportModel;
use App\Models\SubscriptionModel;
use App\Models\UserModel;

class StatisticsController extends BaseController
{
    public function get(): bool|string
    {
        if(!$this->isAdmin()){
            http_response_code(401);
            return json_encode(['error' => 'Token invalid']);
        }

        try {
            $response = [
                'users' => (new UserModel())->count(),
                'messagesSend' => (new MessagesModel())->count(),
                'messagesSendToday' => (new MessagesModel())->countToday(),
                'reports' => (new ReportModel())->count(),
                'reportUnsolved' => (new ReportModel())->countUnsolved(),
                'matchs' => (new MatchModel())->count(),
                'subcrib' => (new SubscriptionModel())->count(),
            ];
        } catch (Exception $e) {
            error_log("Error fetching dashboard stats: " . $e->getMessage());
            $response = ['error' => 'Unable to fetch statistics'];
        }

        return json_encode($response);
    }
}