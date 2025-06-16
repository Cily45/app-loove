<?php

namespace App\Models;

class InflowModel extends BaseModel
{

    public function count()
    {
        return $this
            ->query("SELECT SUM(cash) AS total FROM inflow")
            ->fetch();
    }

    public function add(float $cash)
    {
        return $this
            ->query("INSERT INTO inflow (cash) VALUES (:cash)")
            ->execute([
                "cash" => $cash
            ]);
    }
}