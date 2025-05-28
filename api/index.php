<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-access-token');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

use App\Controllers\HobbiesController;
use App\Controllers\MatchContoller;
use App\Controllers\MessagesController;
use App\Controllers\PriceController;
use App\Controllers\ReportController;
use App\Controllers\ReportReasonController;
use App\Controllers\SubscriptionController;
use Dotenv\Dotenv;
use App\Controllers\AuthController;
use App\Controllers\GenderController;
use App\Controllers\UsersController;
use App\Core\Routeur;
use App\Kernel;

require 'vendor/autoload.php';
require_once __DIR__ . '/src/utils/helper.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


$routeur = new Routeur();

$routeur->addRoute(['POST'], '/login', AuthController::class, 'login');

$routeur->addRoute(['GET'], '/users/{id}', UsersController::class, 'user');
$routeur->addRoute(['GET'], '/users', UsersController::class, 'liste');
$routeur->addRoute(['GET'], '/profil/{id}', UsersController::class, 'profil');
$routeur->addRoute(['GET'], '/profils', UsersController::class, 'allProfil');
$routeur->addRoute(['POST'], '/email', UsersController::class, 'isEmailUsed');
$routeur->addRoute(['POST'], '/create-user', UsersController::class, 'create');
$routeur->addRoute(['POST'], '/users-messages', UsersController::class, 'allProfilMessage');
$routeur->addRoute(['GET'], '/matchs', UsersController::class, 'matchs');
$routeur->addRoute(['POST'], '/update-location', UsersController::class, 'updateLocation');

$routeur->addRoute(['GET'], '/genders', GenderController::class, 'liste');

$routeur->addRoute(['GET'], '/hobbies', HobbiesController::class, 'liste');
$routeur->addRoute(['GET'], '/user-hobbies', HobbiesController::class, 'get');

$routeur->addRoute(['POST'], '/messages', MessagesController::class, 'messages');
$routeur->addRoute(['GET'], '/messages/{id}', MessagesController::class, 'messagesById');
$routeur->addRoute(['POST'], '/send-message', MessagesController::class, 'addMessage');
$routeur->addRoute(['GET'], '/vue/{id}', MessagesController::class, 'viewed');

$routeur->addRoute(['POST'], '/match', MatchContoller::class, 'addMatch');

$routeur->addRoute(['GET'], '/report-reason', ReportReasonController::class, 'liste');

$routeur->addRoute(['POST'], '/report', ReportController::class, 'create');

$routeur->addRoute(['GET'], '/is-subscribe', SubscriptionController::class, 'isActif');
$routeur->addRoute(['GET'], '/subscription-info', SubscriptionController::class, 'info');
$routeur->addRoute(['GET'], '/subscription', SubscriptionController::class, 'subcription');

$routeur->addRoute(['GET'], '/price', PriceController::class, 'get');

new Kernel($routeur);