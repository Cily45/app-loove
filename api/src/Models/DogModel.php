<?php

namespace App\Models;

class DogModel extends BaseModel
{
    public function get(int $id)
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
                'id'=>$id
            ]);
    }
}