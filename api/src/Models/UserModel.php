<?php

namespace App\Models;

use DateTime;
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

    public function all(int $quantity, int $page): array
    {
        $offset = ($page - 1) * $quantity;

        return $this
            ->query("SELECT
            user.id,
            user.lastname,
            user.firstname,
            user.profil_photo,
            user.description,
            user.birthday,
            CASE
                WHEN banned.end_date IS NOT NULL AND banned.end_date > NOW() THEN TRUE
                ELSE FALSE
            END AS is_banned
        FROM user
        LEFT JOIN banned ON banned.user_id = user.id
        WHERE user.firstname != 'Utilisateur Supprimer'
            AND user.birthday IS NOT NULL 
        LIMIT $quantity OFFSET $offset")
            ->fetchAll();
    }

    public function findByEmail(string $email): ?array
    {
        $result = $this
            ->query("SELECT 
            user.*,
            CASE 
                WHEN banned.end_date IS NOT NULL AND banned.end_date > NOW() THEN TRUE
                ELSE FALSE
            END AS is_banned
        FROM user
        LEFT JOIN banned ON banned.user_id = user.id
        WHERE user.email = :email")
            ->fetch(['email' => $email]);

        return $result ?: null;
    }

    public function createUser(
        string   $firstname,
        string   $lastname,
        DateTime $birthday,
        int|null $gender,
        string   $email,
        string   $password
    ): void
    {

        $stmt = $this->query("
        INSERT INTO `user`(`firstname`, `lastname`, `gender_id`, `birthday`, `password`, `email`) 
        VALUES (:firstname, :lastname, :gender, :birthday, :password, :email)
    ");

        $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'gender' => $gender,
            'birthday' => $birthday->format('Y-m-d'),
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

    public function getAllProfilMessage(int $id)
    {
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

    public function getAllProfilMatch(int $id)
    {
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

    public function updateLocation(int $id, string $location)
    {
        try {
            $stmt = $this->query("
                                    UPDATE `user` 
                                    SET `location`= ST_GeomFromText(:location)
                                    WHERE id = :id");
            $stmt->execute(['id' => $id, 'location' => $location]);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function updateBanned(int $id)
    {
        return $this->query('UPDATE users
SET is_banned = 1 - is_banned
WHERE id =:id;
')
            ->execute(['id' => $id]);
    }

    public function delete(int $id)
    {
        $this->query('DELETE FROM `matches` WHERE `user_id_0` = :id OR `user_id_1` = :id;')
            ->execute(['id' => $id]);

        $this->query('DELETE FROM `filter` WHERE `user_id` = :id;')
            ->execute(['id' => $id]);

        $this->query('DELETE FROM `user_hobbies` WHERE `user_id` = :id;')
            ->execute(['id' => $id]);

        $this->query('DELETE FROM `user_gender_preferences` WHERE `user_id` = :id;')
            ->execute(['id' => $id]);

        $this->query('DELETE FROM `user_dog` WHERE `user_id` = :id;')
            ->execute(['id' => $id]);

        $this->query('DELETE FROM dog
WHERE id NOT IN (SELECT dog_id FROM user_dog);')
            ->execute();

        return $this->query('UPDATE `user` SET `firstname`="Utilisateur supprimer",`lastname`=NULL,`gender_id`= NULL,`birthday`=NULL,`profil_photo`="user-delete.webp",`password`=NULL,`email`=NULL,`description`=NULL,`location`=NULL WHERE id = :id')
            ->execute(['id' => $id]);
    }

    public function count(): int
    {
        return $this->query('SELECT COUNT(*) FROM `user` WHERE birthday IS NOT NULL;')
            ->fetchColumn();
    }

    public function getProfilAdmin(string $id): array
    {
        $result = $this
            ->query("SELECT id, firstname, lastname, birthday, email FROM user WHERE id= :id")
            ->fetch(['id' => $id]);

        if (empty($result)) {
            throw new Exception("User with id $id does not exist");
        }

        return $result;
    }

    public function updateUser(
        int      $id,
        string   $firstname,
        string   $lastname,
        DateTime $birthday,
        string   $email,
    ): bool | string
    {
        try {
            $res = $this->query("
      UPDATE `user` 
      SET `firstname`= :firstname,`lastname`= :lastname,`birthday`= :birthday,`email`= :email
      WHERE id= :id
    ")
                ->execute([
                    'id' => $id,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'birthday' => $birthday->format('Y-m-d'),
                    'email' => $email
                ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return $res;
    }

}