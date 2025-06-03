<?php

namespace App\Models;

class DogModel extends BaseModel
{
    public function get(int $id): array
    {
        return $this->query('SELECT
                                        *
                                    FROM
                                        `user_dog`
                                    JOIN
                                        dog 
                                    ON
                                        dog.id = user_dog.dog_id
                                    WHERE
                                        user_dog.user_id = :id;')
            ->fetchAll([
                'id' => $id
            ]);
    }

    public function delete(int $id): bool
    {
        $this->query('DELETE FROM `user_dog` WHERE `user_id` = :id;')
            ->execute(['id' => $id]);

        return $this->query('DELETE FROM dog
WHERE id NOT IN (SELECT dog_id FROM user_dog);')
            ->execute();
    }

    public function add(int $id, string $name, string $birthday, int $gender, int $size, int $temperament): bool
    {
        $stmt = $this->query("INSERT INTO `dog`(`name`, `birthday`, `gender`, `size`, `temperament`)
VALUES (:name, :birthday, :gender, :size, :temperament);");
        $stmt->execute([
            'name' => $name,
            'birthday' => $birthday,
            'gender' => $gender,
            'size' => $size,
            'temperament' => $temperament,
        ]);
        $id_dog = $this->lastInsertId();
        if (!$id_dog) {
            return false;
        }

        $stmt1 = $this->query("INSERT INTO `user_dog`(`user_id`, `dog_id`) VALUES (:id, :id_dog); ");
        $stmt1->execute([
            'id' => $id,
            'id_dog' => $id_dog,
        ]);

        return true;
    }

}