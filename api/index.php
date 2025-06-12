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

use App\Controllers\AuthAdminController;
use App\Controllers\AuthPusherController;
use App\Controllers\BannedController;
use App\Controllers\DogController;
use App\Controllers\DogGenderController;
use App\Controllers\DogSizeController;
use App\Controllers\DogTemperamentController;
use App\Controllers\FilterController;
use App\Controllers\HobbiesController;
use App\Controllers\MailController;
use App\Controllers\MatchContoller;
use App\Controllers\MessagesController;
use App\Controllers\PriceController;
use App\Controllers\ReportController;
use App\Controllers\ReportReasonController;
use App\Controllers\StatisticsController;
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

// AUTH
$routeur->addRoute(['POST'], '/login', AuthController::class, 'login');
$routeur->addRoute(['POST'], '/login-admin', AuthAdminController::class, 'login');
$routeur->addRoute(['POST'], '/auth-pusher', AuthPusherController::class, 'authPusher');
$routeur->addRoute(['GET'], '/beams-token', AuthController::class, 'beamsToken');

// USER
$routeur->addRoute(['GET'], '/users/{id}', UsersController::class, 'user');
$routeur->addRoute(['GET'], '/users/{quantity:\d+}/{page:\d+}', UsersController::class, 'liste');
$routeur->addRoute(['GET'], '/profil/{id}', UsersController::class, 'profil');
$routeur->addRoute(['GET'], '/profils', UsersController::class, 'allProfil');
$routeur->addRoute(['GET'], '/matchs', UsersController::class, 'matchs');
$routeur->addRoute(['GET'], '/profil-admin/{id}', UsersController::class, 'profilAdmin');
$routeur->addRoute(['GET'], '/user-count', UsersController::class, 'count');
$routeur->addRoute(['GET'], '/email/{email}', UsersController::class, 'isEmailUsed');
$routeur->addRoute(['GET'], '/user-notifications', UsersController::class, 'getNotifications');

$routeur->addRoute(['POST'], '/update', UsersController::class, 'update');
$routeur->addRoute(['POST'], '/update-location', UsersController::class, 'updateLocation');
$routeur->addRoute(['POST'], '/create-user', UsersController::class, 'create');
$routeur->addRoute(['POST'], '/update-user-admin', UsersController::class, 'updateProfilAdmin');
$routeur->addRoute(['POST'], '/update-photo', UsersController::class, 'updatePhoto');
$routeur->addRoute(['POST'], '/update-user', UsersController::class, 'updateUser');
$routeur->addRoute(['POST'], '/update-verify', UsersController::class, 'updateVerify');
$routeur->addRoute(['POST'], '/update-password', UsersController::class, 'updatePassword');
$routeur->addRoute(['POST'], '/reset-password', UsersController::class, 'resetPassToken');

$routeur->addRoute(['DELETE'], '/delete/{id}', UsersController::class, 'delete');

//GENDER
$routeur->addRoute(['GET'], '/genders', GenderController::class, 'liste');
$routeur->addRoute(['GET'], '/genders-user/{id}', GenderController::class, 'get');

//HOBBY
$routeur->addRoute(['GET'], '/hobbies', HobbiesController::class, 'liste');
$routeur->addRoute(['GET'], '/user-hobbies/{id}', HobbiesController::class, 'get');

//MESSAGE
$routeur->addRoute(['GET'], '/messages', MessagesController::class, 'messages');
$routeur->addRoute(['GET'], '/messages/{id0}/{id1}', MessagesController::class, 'messagesById');
$routeur->addRoute(['POST'], '/send-message', MessagesController::class, 'addMessage');
$routeur->addRoute(['GET'], '/vue/{id}', MessagesController::class, 'viewed');

//MATCH
$routeur->addRoute(['POST'], '/match', MatchContoller::class, 'addMatch');

//REPORT
$routeur->addRoute(['GET'], '/report-reason', ReportReasonController::class, 'liste');
$routeur->addRoute(['PUT'], '/report', ReportController::class, 'create');
$routeur->addRoute(['GET'], '/reports/{quantity:\d+}/{page:\d+}', ReportController::class, 'list');
$routeur->addRoute(['GET'], '/report/{id}', ReportController::class, 'get');
$routeur->addRoute(['PUT'], '/report-update', ReportController::class, 'update');
$routeur->addRoute(['GET'], '/report-count', ReportController::class, 'count');

//SUSCRIPTION
$routeur->addRoute(['GET'], '/is-subscribe', SubscriptionController::class, 'isActif');
$routeur->addRoute(['GET'], '/subscription-info', SubscriptionController::class, 'info');
$routeur->addRoute(['POST'], '/subscription', SubscriptionController::class, 'subcription');

//PRICE
$routeur->addRoute(['GET'], '/prices', PriceController::class, 'get');
$routeur->addRoute(['POST'], '/prices-update', PriceController::class, 'update');

//DOG
$routeur->addRoute(['GET'], '/dogs/{id}', DogController::class, 'getAll');
$routeur->addRoute(['POST'], '/add-dogs', DogController::class, 'create');
$routeur->addRoute(['GET'], '/dog-sizes', DogSizeController::class, 'liste');
$routeur->addRoute(['GET'], '/dog-genders', DogGenderController::class, 'liste');
$routeur->addRoute(['GET'], '/dog-temperaments', DogTemperamentController::class, 'liste');

//STAT
$routeur->addRoute(['GET'], '/statistics', StatisticsController::class, 'get');

//BANNED
$routeur->addRoute(['PUT'], '/banned-add', BannedController::class, 'add');
$routeur->addRoute(['DELETE'], '/banned-delete/{id}', BannedController::class, 'delete');

//FILTER
$routeur->addRoute(['GET'], '/get-filter', FilterController::class, 'get');
$routeur->addRoute(['POST'], '/add-filter', FilterController::class, 'add');
$routeur->addRoute(['GET'], '/reset-filter', FilterController::class, 'reset');

//MAIL
$routeur->addRoute(['POST'], '/send-contact', MailController::class, 'sendContact');

new Kernel($routeur);