<?php

namespace App\Controllers;

use App\Models\GenderModel;
use App\Models\HobbiesModel;
use App\Models\UserModel;
use DateTime;

class UsersController extends BaseController
{

    public function liste(int $quantity, int $page)
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $model = new UserModel();
            $offset = ($page - 1) * $quantity;
            $result = $model->all($quantity, $offset);
            return json_encode($result, JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            error_log("Erreur avec la recuperations de utilisateurs: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur'], JSON_PRETTY_PRINT);
        }

    }

    public function user(string $id)
    {
        if (!$this->isAdmin() && !$this->verifyToken()) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $model = new UserModel();
            $result = $model->get($id);
            return json_encode($model->get($id), JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la recuperation de l'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur'], JSON_PRETTY_PRINT);
        }
    }

    public function isEmailUsed(string $email): bool|string
    {
        try {
            $email = trim($email);
            $userModel = new UserModel();
            $response = $userModel->findByEmail($email);
            if (is_array($response)) {
                http_response_code(200);
                return json_encode(true, JSON_PRETTY_PRINT);
            }
            http_response_code(200);
            return json_encode((bool)$response, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la verification de mail" . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function create(): string
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $firstname = isset($data['firstname']) ? cleanString($data['firstname']) : null;
        $lastname = isset($data['lastname']) ? cleanString($data['lastname']) : null;
        $birthday = isset($data['birthday']) ? DateTime::createFromFormat('Y-m-d', cleanString($data['birthday'])) : null;
        $gender = isset($data['gender']) ? (int)cleanString($data['gender']) : null;
        $genderPreference = isset($data['sexualOrientation']) ? (int)cleanString($data['sexualOrientation']) : null;
        $email = isset($data['email']) ? cleanString($data['email']) : null;
        $password = isset($data['password']) ? trim($data['password']) : bin2hex(random_bytes(10));
        try {
            $userModel = new UserModel();

            if (!$firstname || !$lastname || !$birthday || !$email || !$password || !$gender) {
                http_response_code(400);
                return json_encode(['error' => 'Champs manquants.']);
            }

            $result = $userModel->findByEmail($email);

            if (is_array($result)) {
                http_response_code(409);
                return json_encode(['error' => 'Email déjà utilisé.']);
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(32));

            $userId = $userModel->createUser(
                $firstname,
                $lastname,
                $birthday,
                $gender,
                $email,
                $hashedPassword,
                $token
            );

            $genderModel = new GenderModel();
            $gendePref = $genderModel->add($genderPreference, $userId);
            $mailController = new MailController();
            $mailController->sendConfirm($token, $email);

            http_response_code(201);
            return json_encode(['success' => true], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la création du profil" . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function profil(string $id)
    {
        try {
            $userId = $this->getId();
            if (!$userId) {
                return null;
            }

            $model = new UserModel();
            return json_encode($model->getProfil($id));

        } catch (\Exception $e) {
            error_log("Erreur avec la récuperation du profil" . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function allProfil()
    {
        try {
            $id = $this->getId();
            if (!$id) {
                http_response_code(401);
                return json_encode(['error' => 'Erreur de token']);
            }
            $model = new UserModel();
            $res = json_encode($model->getAllProfil($id));
            return $res;

        } catch (\Exception $e) {
            error_log("Erreur avec la recuperation des profils" . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function matchs()
    {
        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $model = new UserModel();
            $result = $model->getAllProfilMatch($id);
            return json_encode($result, JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            error_log("Erreur avec la recuperation des profil matché: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur'], JSON_PRETTY_PRINT);
        }
    }

    public function updateLocation()
    {
        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $location = isset($data['location']) ? cleanString($data['location']) : null;
            if (!$location) {
                http_response_code(400);
                return json_encode(['error' => 'Champs manquants.']);
            }

            $model = new UserModel();
            $result = $model->updateLocation($id, $location);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la maj de la localisation " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public
    function banned(int $id)
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $model = new UserModel();
            $result = $model->updateBanned($id);
            return json_encode($result);
        } catch (\Exception $e) {
            error_log("Erreur avec le comptage d'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public
    function delete(int $idUser): bool|string
    {
        if (!$this->isAdmin()) {
            $id = $this->getId();
            if (!$id || (int)$id !== $idUser) {
                http_response_code(401);
                return json_encode(['error' => 'Erreur de token']);
            }
        }

        try {
            $uploadDir = __DIR__ . '/../../uploads/photo-user/';
            $targetPath = $uploadDir . 'profil-photo-' . $idUser . '.webp';

            if (file_exists($targetPath)) {
                unlink($targetPath);
            }

            $model = new UserModel();
            $result = $model->delete($idUser);

            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la suppression d'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public
    function profilAdmin(int $id): bool|string
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $model = new UserModel();
            $result = $model->getProfilAdmin($id);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la récupération du profil: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public
    function updateProfilAdmin(): bool|string
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = isset($data['id']) ? (int)$data['id'] : null;
            $firstname = isset($data['firstname']) ? cleanString($data['firstname']) : null;
            $lastname = isset($data['lastname']) ? cleanString($data['lastname']) : null;
            $birthday = isset($data['birthday']) ? DateTime::createFromFormat('Y-m-d', cleanString($data['birthday'])) : null;
            $email = isset($data['email']) ? cleanString($data['email']) : null;

            if (!$firstname || !$lastname || !$birthday || !$email || !$id) {
                http_response_code(400);
                return json_encode(['error' => 'Champs manquants.']);
            }

            $userModel = new UserModel();
            $result = $userModel->updateUser($id, $firstname, $lastname, $birthday, $email);
            http_response_code(201);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la maj d'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public
    function count(): string
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }
        try {
            $model = new UserModel();
            $result = $model->count();
            http_response_code(200);
            return json_encode($result, JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            error_log("Erreur avec le comptage d'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public
    function updatePhoto()
    {
        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        if (isset($_FILES['photo'])) {
            $uploadDir = __DIR__ . '/../../uploads/photo-user/';
            $tmpName = $_FILES['photo']['tmp_name'];
            $fileName = 'profil-photo-' . $id . '.webp';
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                    try {
                        $model = new UserModel();
                        $result = $model->updatePhoto($id, $targetPath);
                        http_response_code(200);
                        return json_encode($result, JSON_PRETTY_PRINT);
                    } catch (\Exception $e) {
                        error_log("Erreur avec la mise à jour de la photo: " . $e->getMessage());
                        http_response_code(500);
                        return json_encode(['error' => 'Erreur serveur']);
                    }
            } else {
                http_response_code(500);
                return json_encode(['error' => 'Erreur lors du déplacement du fichier']);
            }
            /*$uploadDir = __DIR__ . '/../../uploads/photo-user/';
            $tmpName = $_FILES['photo']['tmp_name'];
            $fileName = 'profil-photo-' . $id . '.jpg';
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $webpName = pathinfo($fileName, PATHINFO_FILENAME) . '.webp';
                $webpPath = $uploadDir . $webpName;

                if (convertToWebP($targetPath, $webpPath)) {
                    unlink($targetPath);
                    try {
                        $model = new UserModel();
                        $result = $model->updatePhoto($id, $webpName);
                        http_response_code(200);
                        return json_encode($result, JSON_PRETTY_PRINT);
                    } catch (\Exception $e) {
                        error_log("Erreur avec la mise à jour de la photo: " . $e->getMessage());
                        http_response_code(500);
                        return json_encode(['error' => 'Erreur serveur']);
                    }

                } else {
                    return json_encode([
                        'error' => 'Conversion WebP échouée'
                    ]);
                }
            } else {
                http_response_code(500);
                return json_encode(['error' => 'Erreur lors du déplacement du fichier']);
            }*/


        } else {
            http_response_code(400);
            return json_encode(['error' => 'Aucun fichier envoyé']);
        }
    }

    public
    function updateUser(): bool|string
    {
        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $description = isset($data['description']) ? cleanString($data['description']) : null;
            $genders = isset($data['genderPreferences']) && is_array(($data['genderPreferences'])) ? $data['genderPreferences'] : null;
            $hobbies = isset($data['selectedHobbies']) && is_array($data['selectedHobbies']) ? $data['selectedHobbies'] : null;

            if (!$description || !$genders) {
                http_response_code(400);
                return json_encode(['error' => 'Champs manquants ou incorrect.']);
            }

            $userModel = new UserModel();
            $result = $userModel->updateUserDescription($id, $description);

            if (!$result) {
                http_response_code(400);
                return json_encode(['error' => 'Erreur avec la maj description']);
            }

            $genderModel = new GenderModel();
            $result = $genderModel->delete($id);

            if (!$result) {
                http_response_code(400);
                return json_encode(['error' => 'Erreur avec la suppression des preference de genre']);
            }
            foreach ($genders as $index => $gender) {
                if ($gender) {
                    $result = $genderModel->add($index + 1, $id);
                    if (!$result) {
                        http_response_code(400);
                        return json_encode(['error' => 'Erreur avec l\'ajout des preference amoureuseq']);
                    }
                }
            }

            $hobbiesModel = new HobbiesModel();
            $result = $hobbiesModel->delete($id);
            if (!$result) {
                http_response_code(400);
                return json_encode(['error' => 'Erreur avec la suppression des hobbies/utilsateur']);
            }

            foreach ($hobbies as $hobby) {
                $result = $hobbiesModel->add($id, $hobby['id']);
                if (!$result) {
                    http_response_code(400);
                    return json_encode(['error' => 'Erreur avec l\'ajout des preference amoureuseq']);
                }
            }
            $result = $userModel->getProfil($id);
            http_response_code(201);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la maj d'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function updateVerify(): bool|string
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $token = $data['token'] ?? null;

            if (!$token) {
                http_response_code(400);
                return json_encode(['error' => 'Aucun token']);
            }

            $model = new UserModel();
            $result = $model->updateVerify($token);
            http_response_code(200);
            return json_encode($result, JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            error_log("Erreur avec la maj d'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function resetPassToken()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'] ?? null;
        if (!$email) {
            http_response_code(400);
            return json_encode(['error' => 'Aucun email']);
        }

        try {
            $model = new UserModel();
            $result = $model->getUserToken($email);

            if ($result) {
                $mailController = new MailController();
                $mailController->sendReset($result['token'], $email);
            }

            http_response_code(200);
            return json_encode(true, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la maj d'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function updatePassword(): bool|string
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $token = $data['token'] ?? null;
            $password = $data['password'] ?? null;
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            if (!$token) {
                http_response_code(400);
                return json_encode(['error' => 'Aucun token']);
            }

            $model = new UserModel();
            $result = $model->updatePassword($token, $hashedPassword);
            http_response_code(200);
            return json_encode($result, JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            error_log("Erreur avec la maj d'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function update()
    {
        $id = $this->getId();

        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $user = [
            'email' => isset($data['email']) ? filter_var($data['email'], FILTER_VALIDATE_EMAIL) : null,
            'password' => isset($data['password']) ? password_hash(trim($data['password']), PASSWORD_DEFAULT) : null,
            'message_push' => isset($data['message_push']) ? (int)$data['message_push'] : null,
            'message_email' => isset($data['message_email']) ? (int)$data['message_email'] : null,
            'match_push' => isset($data['match_push']) ? (int)$data['match_push'] : null,
            'match_email' => isset($data['match_email']) ? (int)$data['match_email'] : null,
        ];

        try {
            $model = new UserModel();
            $result = $model->update($user, $id);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la maj d'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function getNotifications()
    {
        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $model = new UserModel();
            $result = $model->getNotifications($id);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la recuperation des notifications: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }
}