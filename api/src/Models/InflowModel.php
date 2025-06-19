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

    public function getLastYearInflow()
    {
        return $this
            ->query("
            SELECT 
              DATE_FORMAT(date_buy, '%m-%Y') AS mois,
              DATE_FORMAT(date_buy, '%Y-%m') AS moisSort,
              SUM(cash) AS revenu_total
            FROM inflow
            WHERE date_buy >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY mois, moisSort
            ORDER BY moisSort ASC;
            ")
            ->fetchAll();
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