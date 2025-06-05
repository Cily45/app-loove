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
        $quantity = cleanString($quantity);
        $page = cleanString($page);
        $model = new UserModel();
        $offset = ($page - 1) * $quantity;
        $res = $model->all($quantity, $offset);
        return json_encode($res, JSON_PRETTY_PRINT);
    }

    public function user(string $id)
    {
        $model = new UserModel();
        return json_encode($model->get($id), JSON_PRETTY_PRINT);
    }

    public function isEmailUsed(string $email): bool|string
    {
        $email = trim('email');

        $userModel = new UserModel();
        $response = $userModel->findByEmail($email);

        return json_encode(['used' => (bool)$response], JSON_PRETTY_PRINT);
    }

    public function create(): string
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $firstname = cleanString($data['firstname']);
        $lastname = cleanString($data['lastname']);
        $birthday = DateTime::createFromFormat('Y-m-d', cleanString($data['birthday']));
        $gender = isset($data['gender']) ? (int)cleanString($data['gender']) : null;
        $email = cleanString($data['email']);
        $password = isset($data['password']) ? trim($data['password']) : '0000';
        $userModel = new UserModel();

        if (!$firstname || !$lastname || !$birthday || !$email || !$password) {
            http_response_code(400);
            return json_encode(['error' => 'Champs manquants.']);
        }

        if ($userModel->findByEmail($email)) {
            http_response_code(409);
            return json_encode(['error' => 'Email déjà utilisé.']);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userModel->createUser(
            $firstname,
            $lastname,
            $birthday,
            $gender,
            $email,
            $hashedPassword
        );

        http_response_code(201);
        return json_encode(['success' => true], JSON_PRETTY_PRINT);
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

        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
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

        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur :' . $e->getMessage()]);
        }
    }

    public function matchs()
    {
        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }
        $model = new UserModel();

        return json_encode($model->getAllProfilMatch($id));
    }

    public function updateLocation()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        $location = cleanString($data['location']);
        $model = new UserModel();

        return json_encode($model->updateLocation($id, $location));
    }

    public function banned(int $id)
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

    public function delete(int $id): bool|string
    {
        if (!$this->isAdmin()) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $uploadDir = __DIR__ . '/../../uploads/photo-user/';
            $targetPath = $uploadDir . 'profil-photo-' . $id . '.webp';
            unlink($targetPath);

            $model = new UserModel();
            $result = $model->delete($id);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la suppression d'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

    public function profilAdmin(int $id): bool|string
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

    public function updateProfilAdmin(): bool|string
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

    public function count(): string
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

    public function updatePhoto()
    {
        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        if (isset($_FILES['photo'])) {
            $uploadDir = __DIR__ . '/../../uploads/photo-user/';
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
                    echo json_encode([
                        'error' => 'Conversion WebP échouée'
                    ]);
                }
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Erreur lors du déplacement du fichier']);
            }


        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Aucun fichier envoyé']);
        }
    }

    public function updateUser(): bool|string
    {
        $id = $this->getId();
        if (!$id) {
            http_response_code(401);
            return json_encode(['error' => 'Erreur de token']);
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $description = isset($data['description']) ? cleanString($data['description']) : null;
            $genders = isset($data['genderPreferences']) &&  is_array(($data['genderPreferences']))? $data['genderPreferences'] : null;
            $hobbies = isset($data['selectedHobbies']) && is_array($data['selectedHobbies']) ? $data['selectedHobbies'] : null;

            if (!$description || !$genders) {
                http_response_code(400);
                return json_encode(['error' => 'Champs manquants ou incorrect.']);
            }

            $userModel = new UserModel();
            $result = $userModel->updateUserDescription($id, $description);

            if(!$result){
                http_response_code(400);
                return json_encode(['error' => 'Erreur avec la maj description']);
            }

            $genderModel = new GenderModel();
            $result = $genderModel->delete($id);

            if(!$result){
                http_response_code(400);
                return json_encode(['error' => 'Erreur avec la suppression des preference de genre']);
            }
            foreach ($genders as $index => $gender) {
                if($gender){
                    $result = $genderModel->add($index + 1, $id);
                    if(!$result){
                        http_response_code(400);
                        return json_encode(['error' => 'Erreur avec l\'ajout des preference amoureuseq']);
                    }
                }
            }

            $hobbiesModel = new HobbiesModel();
            $result = $hobbiesModel->delete($id);
            if(!$result){
                http_response_code(400);
                return json_encode(['error' => 'Erreur avec la suppression des hobbies/utilsateur']);
            }

            foreach ($hobbies as $hobby) {
                $result = $hobbiesModel->add( $id, $hobby['id']);
                if(!$result){
                    http_response_code(400);
                    return json_encode(['error' => 'Erreur avec l\'ajout des preference amoureuseq']);
                }
            }

            http_response_code(201);
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Erreur avec la maj d'utilisateur: " . $e->getMessage());
            http_response_code(500);
            return json_encode(['error' => 'Erreur serveur']);
        }
    }

}