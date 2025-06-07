<?php

namespace App\Models;

class FilterModel extends BaseModel
{
    public function get(int $id): array
    {
        return [
            'dog_sizes' => $this
                ->query("
                SELECT dog_sizes.id, dog_sizes.name,
                CASE WHEN user_filter_dog_sizes.user_id IS NOT NULL THEN TRUE ELSE FALSE END AS selected
                FROM dog_sizes
                LEFT JOIN user_filter_dog_sizes ON user_filter_dog_sizes.size_dog_id = dog_sizes.id AND user_filter_dog_sizes.user_id = :id
                ORDER BY dog_sizes.id;")
                ->fetchAll(['id' => $id]),

            'dog_genders' => $this
                ->query("
                SELECT dog_genders.id, dog_genders.name,
                CASE WHEN user_filter_dog_genders.user_id IS NOT NULL THEN TRUE ELSE FALSE END AS selected
                FROM dog_genders
                LEFT JOIN user_filter_dog_genders ON user_filter_dog_genders.dog_gender_id = dog_genders.id AND user_filter_dog_genders.user_id = :id
                ORDER BY dog_genders.id;
                ")
                ->fetchAll([
                    'id' => $id
                ]),
            'dog_temperaments' => $this
                ->query("  
                SELECT dog_temperaments.id, dog_temperaments.name,
                CASE WHEN user_filter_dog_temperament.user_id IS NOT NULL THEN TRUE ELSE FALSE END AS selected
                FROM dog_temperaments
                LEFT JOIN user_filter_dog_temperament ON user_filter_dog_temperament.dog_temperament_id = dog_temperaments.id AND user_filter_dog_temperament.user_id = :id
                ORDER BY dog_temperaments.id;")
                ->fetchAll(['id' => $id]),

            'filter' => $this
                ->query("SELECT * FROM user_filter WHERE user_id = :id")
                ->fetch(['id' => $id]),

            'genders' => $this
                ->query(" 
                    SELECT gender.id, gender.name,
                    CASE WHEN user_filter_gender.user_id IS NOT NULL THEN TRUE ELSE FALSE END AS selected
                    FROM gender
                    LEFT JOIN user_filter_gender ON user_filter_gender.gender_id = gender.id AND user_filter_gender.user_id = :id
                    ORDER BY gender.id;")
                ->fetchAll(['id' => $id]),

            'hobbies' => $this
                ->query("
                SELECT hobbies.id, hobbies.name, hobbies.icon,
                CASE WHEN user_filter_hobbies.user_id IS NOT NULL THEN TRUE ELSE FALSE END AS selected
                FROM hobbies
                LEFT JOIN user_filter_hobbies ON user_filter_hobbies.hobbies_id = hobbies.id AND user_filter_hobbies.user_id = :id
                ORDER BY hobbies.id;")
                ->fetchAll(['id' => $id]),
        ];
    }

    public function filterReset(
        int $id
    ): bool
    {
        try {
            $this->query("DELETE FROM user_filter_dog_sizes WHERE user_id = :id")->execute(['id' => $id]);
            $this->query("DELETE FROM user_filter_dog_genders WHERE user_id = :id")->execute(['id' => $id]);
            $this->query("DELETE FROM user_filter_dog_temperament WHERE user_id = :id")->execute(['id' => $id]);
            $this->query("UPDATE `user_filter` SET `min_age`=DEFAULT,`max_age`=DEFAULT,`distance`=DEFAULT WHERE user_id = :id")->execute(['id' => $id]);
            $this->query("DELETE FROM user_filter_gender WHERE user_id = :id")->execute(['id' => $id]);
            $this->query("DELETE FROM user_filter_hobbies WHERE user_id = :id")->execute(['id' => $id]);
            return true;
        } catch (\Exception $e) {
            error_log("Erreur avec la maj du filtre de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    public function addGender(
        int $id,
        int $gender_id,
    ): bool
    {
        return $this
            ->query("  
                INSERT INTO `user_filter_gender`(`user_id`, `gender_id`) 
                VALUES (:id, :gender_id)
                ")
            ->execute([
                'id' => $id,
                'gender_id' => $gender_id
            ]);
    }

    public function addHobbies(
        int $id,
        int $hobby_id,
    ): bool
    {
        return $this
            ->query("  
                INSERT INTO `user_filter_hobbies`(`user_id`, `hobbies_id`) 
                VALUES (:id, :hobbies_id)
                ")
            ->execute([
                'id' => $id,
                'hobbies_id' => $hobby_id
            ]);
    }

    public function addDogGender(
        int $id,
        int $dogGender_id,
    ): bool
    {
        return $this
            ->query("  
                INSERT INTO `user_filter_dog_genders`(`user_id`, `dog_gender_id`) 
                VALUES (:id, :dog_gender_id)
                ")
            ->execute([
                'id' => $id,
                'dog_gender_id' => $dogGender_id
            ]);
    }

    public function addDogSize(
        int $id,
        int $size_dog_id,
    ): bool
    {
        return $this
            ->query("  
                INSERT INTO `user_filter_dog_sizes`(`user_id`, `size_dog_id`) 
                VALUES (:id, :size_dog_id)
                ")
            ->execute([
                'id' => $id,
                'size_dog_id' => $size_dog_id
            ]);
    }

    public function addDogTemperament(
        int $id,
        int $dog_temperament_id,
    ): bool
    {
        return $this
            ->query("  
                INSERT INTO `user_filter_dog_temperament`(`user_id`, `dog_temperament_id`) 
                VALUES (:id, :dog_temperament_id)
                ")
            ->execute([
                'id' => $id,
                'dog_temperament_id' => $dog_temperament_id
            ]);
    }

    public function addFilter(
        int $id,
        int $minAge,
        int$maxAge,
        int $distance,
    ): bool
    {
        return $this
            ->query("  
                UPDATE `user_filter` SET 
                `user_id`=:id,
                `min_age`=:minAge,
                `max_age`=:maxAge,
                `distance`=:distance 
                WHERE user_id = :id
                ")
            ->execute([
                'id' => $id,
                'minAge' => $minAge,
                'maxAge' => $maxAge,
                'distance' => $distance
            ]);
    }
}