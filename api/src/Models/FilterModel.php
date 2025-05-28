<?php

namespace App\Models;

use App\Controllers\FilterController;

class FilterModel extends FilterController
{
 public function get(int $id){
     return $this
         ->query("SELECT * FROM filter WHERE user_id = :id")
         ->fetchAll();
 }


    public function create(int $id, string $type, string $info){
        return $this
            ->query("UPDATE `filter` SET `user_id`= :id,`type`= :type,`info`= :info")
            ->execute([
                'id'=>$id,
                'type'=>$type,
                'info'=>$info
            ]);
    }

    public function delete(int $id): bool|string
    {
        return $this
            ->query("DELETE FROM `filter` WHERE `user_id` = :id")
            ->execute([
                'id'=>$id
            ]);
    }
}