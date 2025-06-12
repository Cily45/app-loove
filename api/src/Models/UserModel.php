<?php

namespace App\Models;

use DateTime;
use Exception;

class UserModel extends BaseModel
{
    public function get(
        string $id
    ): array
    {
        return $this->query("
            SELECT * 
            FROM user 
            WHERE id = :id
            ")
            ->fetch([
                    'id' => $id]
            );
    }

    public function all(
        int $quantity,
        int $offset
    ): array
    {
        return $this
            ->query("
            SELECT
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
            LIMIT $quantity OFFSET $offset
        ")
            ->fetchAll();
    }

    public function findByEmail(
        string $email
    ): array|bool
    {
        return $this->query("
            SELECT 
                user.*,
                CASE 
                    WHEN banned.end_date IS NOT NULL AND banned.end_date > NOW() THEN TRUE
                    ELSE FALSE
                END AS is_banned
            FROM user
            LEFT JOIN banned ON banned.user_id = user.id
            WHERE user.email = :email
        ")
            ->fetch([
                'email' => $email
            ]);
    }

    public function createUser(
        string   $firstname,
        string   $lastname,
        DateTime $birthday,
        ?int     $gender,
        string   $email,
        string   $password,
        string   $token
    ): bool
    {
        $result = $this
            ->query("
                INSERT INTO user (firstname, lastname, gender_id, birthday, password, email, token)
                VALUES (:firstname, :lastname, :gender, :birthday, :password, :email, :token)
                ")
            ->execute([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'gender' => $gender,
                'birthday' => $birthday->format('Y-m-d'),
                'password' => $password,
                'email' => $email,
                'token' => $token
            ]);

        $id = $this->lastInsertId();
        if (!$result) {
            return $result;
        }

        return $this
            ->query("
                INSERT INTO `user_filter`(`user_id`)
                VALUES (:id)")
            ->execute([
                'id' => $id
            ]);
    }

    public function getProfil(
        string $id
    ): array|bool
    {
        return $this
            ->query("
            SELECT
                user.id,
                user.firstname,
                user.lastname,
                user.profil_photo,
                user.description,
                user.birthday,
                gender.name AS gender
            FROM user
            JOIN gender ON user.gender_id = gender.id
            WHERE user.id = :id
            ")
            ->fetch(['id' => $id]);
    }

    public function getAllProfil(
        int $id
    ): array
    {
        return $this
            ->query("
SELECT DISTINCT
    u.id, 
    u.firstname, 
    u.lastname, 
    u.gender_id, 
    u.birthday, 
    u.profil_photo, 
    u.description,
    TIMESTAMPDIFF(YEAR, u.birthday, CURDATE()) as age,
    -- Calcul de la distance en km
    ROUND(
        ST_Distance_Sphere(
            u.location, 
            (SELECT location FROM user WHERE id = :id)
        ) / 1000, 2
    ) as distance_km
FROM user u
-- Suppression des LEFT JOIN qui causent les doublons
WHERE u.id != :id 
    AND u.lastname IS NOT NULL
    AND u.profil_photo IS NOT NULL
    AND u.is_verified = 1
        AND u.id NOT IN (
        SELECT user_id_1 
        FROM matches 
        WHERE user_id_0 = :id
    )
    -- Filtre d'âge (si des filtres existent)
    AND (
        NOT EXISTS (SELECT 1 FROM user_filter WHERE user_id = :id)
        OR (
            TIMESTAMPDIFF(YEAR, u.birthday, CURDATE()) >= (SELECT min_age FROM user_filter WHERE user_id = :id LIMIT 1)
            AND TIMESTAMPDIFF(YEAR, u.birthday, CURDATE()) <= (SELECT max_age FROM user_filter WHERE user_id = :id LIMIT 1)
        )
    )
    -- Filtre de distance (si des filtres existent)
 --   AND (
  --      NOT EXISTS (SELECT 1 FROM user_filter WHERE user_id = :id AND distance IS NOT NULL)
  --      OR ST_Distance_Sphere(
   --         u.location, 
   --         (SELECT location FROM user WHERE id = :id)
    --    ) / 1000 <= (SELECT distance FROM user_filter WHERE user_id = :id AND distance IS NOT NULL LIMIT 1)
  --  )
    -- Filtre de genre (si des filtres existent)
    AND (
        NOT EXISTS (SELECT 1 FROM user_filter_gender WHERE user_id = :id)
        OR u.gender_id IN (
            SELECT gender_id FROM user_filter_gender WHERE user_id = :id
        )
    )
    -- Filtre des hobbies (si des filtres existent)
    AND (
        NOT EXISTS (SELECT 1 FROM user_filter_hobbies WHERE user_id = :id)
        OR EXISTS (
            SELECT 1 
            FROM user_hobbies uh 
            WHERE uh.user_id = u.id 
            AND uh.hobbies_id IN (
                SELECT hobbies_id FROM user_filter_hobbies WHERE user_id = :id
            )
        )
    )
    -- Filtre genre des chiens (si des filtres existent)
    AND (
        NOT EXISTS (SELECT 1 FROM user_filter_dog_genders WHERE user_id = :id)
        OR EXISTS (
            SELECT 1 
            FROM user_dog ud 
            JOIN dog d ON d.id = ud.dog_id 
            WHERE ud.user_id = u.id 
            AND d.gender_id IN (
                SELECT dog_gender_id FROM user_filter_dog_genders WHERE user_id = :id
            )
        )
    )
    -- Filtre taille des chiens (si des filtres existent)
    AND (
        NOT EXISTS (SELECT 1 FROM user_filter_dog_sizes WHERE user_id = :id)
        OR EXISTS (
            SELECT 1 
            FROM user_dog ud 
            JOIN dog d ON d.id = ud.dog_id 
            WHERE ud.user_id = u.id 
            AND d.size_id IN (
                SELECT size_dog_id FROM user_filter_dog_sizes WHERE user_id = :id
            )
        )
    )
    -- Filtre tempérament des chiens (si des filtres existent)
    AND (
        NOT EXISTS (SELECT 1 FROM user_filter_dog_temperament WHERE user_id = :id)
        OR EXISTS (
            SELECT 1 
            FROM user_dog ud 
            JOIN dog d ON d.id = ud.dog_id 
            WHERE ud.user_id = u.id 
            AND d.temperament_id IN (
                SELECT dog_temperament_id FROM user_filter_dog_temperament WHERE user_id = :id
            )
        )
    )
ORDER BY distance_km ASC;
")
            ->fetchAll(['id' => $id]);
    }

    public function getAllProfilMatch(
        int $id
    ): array
    {
        return $this
            ->query("
        SELECT
    u.id,
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
            WHEN user_id_1 = :id THEN 1
            WHEN user_id_0 = :id THEN 2
        END AS direction
    FROM matches
    WHERE (user_id_0 = :id OR user_id_1 = :id)
) AS filtered_matches
JOIN user u ON u.id = filtered_matches.other_user_id
WHERE NOT EXISTS (
    SELECT 1 FROM matches m
    WHERE
        (
            (m.user_id_0 = :id AND m.user_id_1 = u.id) OR
            (m.user_id_1 = :id AND m.user_id_0 = u.id)
        )
        AND m.is_skiped = 1
)
GROUP BY u.id, u.firstname, u.lastname, u.profil_photo, u.description, u.birthday

        ")
            ->fetchAll(['id' => $id]);
    }

    public function updateLocation(
        int    $id,
        string $location
    ): bool
    {

        return $this
            ->query("
                UPDATE user 
                SET location = ST_GeomFromText(:location)
                WHERE id = :id
                ")
            ->execute([
                    'id' => $id,
                    'location' => $location]
            );
    }

    public function updateBanned(
        int $id
    ): bool
    {
        return $this
            ->query("
                UPDATE users
                SET is_banned = 1 - is_banned
                WHERE id = :id
                ")
            ->execute([
                'id' => $id
            ]);
    }

    public function delete(
        int $id
    ): bool
    {
        $this->query("DELETE FROM matches WHERE user_id_0 = :id OR user_id_1 = :id")->execute(['id' => $id]);
        $this->query("DELETE FROM user_hobbies WHERE user_id = :id")->execute(['id' => $id]);
        $this->query("DELETE FROM user_gender_preferences WHERE user_id = :id")->execute(['id' => $id]);
        $this->query("DELETE FROM user_dog WHERE user_id = :id")->execute(['id' => $id]);
        $this->query("DELETE FROM dog WHERE id NOT IN (SELECT dog_id FROM user_dog)")->execute();
        $this->query("DELETE FROM user_filter_dog_sizes WHERE user_id = :id")->execute(['id' => $id]);
        $this->query("DELETE FROM user_filter_dog_genders WHERE user_id = :id")->execute(['id' => $id]);
        $this->query("DELETE FROM user_filter_dog_temperament WHERE user_id = :id")->execute(['id' => $id]);
        $this->query("DELETE FROM user_filter WHERE user_id = :id")->execute(['id' => $id]);
        $this->query("DELETE FROM user_filter_gender WHERE user_id = :id")->execute(['id' => $id]);
        $this->query("DELETE FROM user_filter_hobbies WHERE user_id = :id")->execute(['id' => $id]);

        return $this
            ->query("
            UPDATE user 
            SET firstname = 'Utilisateur supprimer',
                lastname = NULL,
                gender_id = NULL,
                birthday = NULL,
                profil_photo = 'user-delete.webp',
                password = NULL,
                email = NULL,
                description = NULL,
                location = NULL
            WHERE id = :id
        ")
            ->execute([
                'id' => $id
            ]);
    }

    public function count(): int
    {
        return $this
            ->query("
                SELECT COUNT(*) 
                FROM user 
                WHERE birthday IS NOT NULL
                ")
            ->fetchColumn();
    }

    public function getProfilAdmin(
        string $id
    ): array
    {
        return $this
            ->query("
                SELECT id, firstname, lastname, birthday, email
                FROM user
                WHERE id = :id
                ")
            ->fetch([
                'id' => $id
            ]);
    }

    public function updateUser(
        int      $id,
        string   $firstname,
        string   $lastname,
        DateTime $birthday,
        string   $email
    ): bool|string
    {
        return $this
            ->query("
                UPDATE user 
                SET firstname = :firstname,
                    lastname = :lastname,
                    birthday = :birthday,
                    email = :email
                WHERE id = :id
            ")
            ->execute([
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'birthday' => $birthday->format('Y-m-d'),
                'email' => $email
            ]);

    }

    public function updatePhoto(
        int    $id,
        string $photo
    ): bool|string
    {
        return $this
            ->query("
                UPDATE user 
                SET profil_photo = :photo
                WHERE id = :id
            ")
            ->execute([
                'id' => $id,
                'photo' => $photo,
            ]);
    }

    public function updateUserDescription(bool|string $id, string $description): bool
    {
        return $this
            ->query("
                UPDATE user 
                SET description = :description
                WHERE id = :id
            ")
            ->execute([
                'id' => $id,
                'description' => $description,
            ]);
    }

    public function updateVerify(
        string $token
    ): bool
    {

        return $this
            ->query("
                UPDATE `user` SET `is_verified`= 1
                WHERE token = :token
                ")
            ->execute([
                'token' => $token
            ]);
    }

    public function updatePassword(
        string $token,
        string $password
    ): bool
    {

        return $this
            ->query("
                UPDATE `user` SET `password`= :password
                WHERE token = :token
                ")
            ->execute([
                'token' => $token,
                'password' => $password
            ]);
    }

    public function getUserToken(
        string $email
    ): array|bool
    {
        return $this
            ->query("
            SELECT `token`
            FROM `user`
            WHERE email = :email
            ")
            ->fetch([
                'email' => $email
            ]);
    }

    public function update($user, $id): bool
    {
        $setParts = [];

        foreach ($user as $key => $value) {
            if ($value === null) {
                continue;
            }
            $dbColumn = $key;
            $setParts[] = "`{$dbColumn}` = '{$value}'";
        }


        if (empty($setParts)) {
            return false;
        }

        return $this
            ->query("UPDATE `user` SET " . implode(', ', $setParts) . " WHERE `id` = :id")
            ->execute([
                'id' => $id
            ]);
    }

    public function getNotifications(int $id)
    {
        return $this
            ->query("
            SELECT `message_push`, `message_email`, `match_push`, `match_email`
            FROM `user`
            WHERE id = :id
            ")
            ->fetch([
                'id' => $id
            ]);
    }
}