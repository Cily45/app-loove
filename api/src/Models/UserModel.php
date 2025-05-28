<?php

namespace App\Models;

use Exception;

class UserModel extends BaseModel
{

    public function get(string $id): array
    {
        $result = $this
            ->query("SELECT * FROM user WHERE id= :id")
            ->fetch(['id' => $id]);

        if (empty($result)) {
            throw new Exception("User with id $id does not exist");
        }

        return $result;
    }

    public function all(): array
    {
        return $this
            ->query("SELECT * FROM user")
            ->fetchAll();
    }

    public function findByEmail(string $email): ?array
    {
        $result = $this
            ->query("SELECT * FROM user WHERE email = :email")
            ->fetch(['email' => $email]);

        return $result ?: null;
    }

    public function createUser(
        string $firstname,
        string $lastname,
        string $birthday,
        int $gender,
        int $sexualOrientation,
        string $email,
        string $password
    ): void {
        $date = toDate($birthday);

        $stmt = $this->query("
        INSERT INTO `user`(`firstname`, `lastname`, `gender_id`, `sexual_orientation_id`, `birthday`, `password`, `email`) 
        VALUES (:firstname, :lastname, :gender, :sexualorientation, :birthday, :password, :email)
    ");

        $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'gender' => $gender,
            'sexualorientation' => $sexualOrientation,
            'birthday' => $date,
            'password' => $password,
            'email' => $email
        ]);
    }

    public function getProfil(string $id)
    {
        $result = $this
            ->query("SELECT id, firstname, lastname, profil_photo, description, birthday FROM user WHERE id= :id")
            ->fetch(['id' => $id]);

        if (empty($result)) {
            throw new Exception("User with id $id does not exist");
        }

        return $result;
    }
    public function getAllProfil(int $id)
    {
        $result = $this
            ->query("
SELECT `id`, `firstname`, `lastname`, `gender_id`, `birthday`, `profil_photo`, `description`
FROM user
WHERE id != :id
AND id NOT IN (
    SELECT user_id_1
    FROM matches
    WHERE user_id_0 = :id
)
")
            ->fetchAll(['id' => $id]);

        return $result;
    }

    public function getAllProfilMessage(int $id){
        $result = $this
            ->query("
                    SELECT DISTINCT  
                        user.id, user.firstname, user.lastname, user.gender_id, user.birthday, user.profil_photo, 
                     user.description
                    FROM user
                    JOIN messages ON (user.id = messages.sender_id OR user.id = messages.receiver_id)
                    WHERE messages.sender_id = :id OR messages.receiver_id = :id
")
            ->fetchAll(['id' => $id]);
        return $result;
    }

    public function getAllProfilMatch(int $id){
        $result = $this
            ->query("
                   SELECT
  u.id AS id,
  u.firstname,
  u.lastname,
  u.profil_photo,
  u.description,
  u.birthday,
  CASE
    WHEN COUNT(*) = 2 THEN 0
    WHEN MAX(direction) = 1 THEN 1
    WHEN MAX(direction) = 2 THEN 2
  END AS match_code
FROM (
  SELECT
    CASE
      WHEN user_id_0 = :id THEN user_id_1
      ELSE user_id_0
    END AS other_user_id,
    CASE
      WHEN user_id_1 = :id AND is_skiped = 0 THEN 1
      WHEN user_id_0 = :id AND is_skiped = 0 THEN 2
    END AS direction
  FROM matches
  WHERE (user_id_0 = :id OR user_id_1 = :id)
    AND is_skiped = 0
) AS filtered_matches
JOIN user u ON u.id = filtered_matches.other_user_id
GROUP BY u.id, u.firstname, u.lastname, u.profil_photo;
")
            ->fetchAll(['id' => $id]);
        return $result;
    }

    public function updateLocation(int $id, string $location){
        try{
            $stmt = $this->query("
                                    UPDATE `user` 
                                    SET `location`= ST_GeomFromText(:location)
                                    WHERE id = :id");
            $stmt->execute(['id' => $id, 'location' => $location]);
        }catch(Exception $e){
            return false;
        }

        return true;
    }
}