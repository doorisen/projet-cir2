<?php

declare(strict_types=1);



class StatsManager {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getGlobalStats() : array {
        $sql = "
            SELECT
                (SELECT COUNT(*)
                 FROM POINT_DE_RECHARGE) AS nb_pdc,

                (SELECT COUNT(DISTINCT nom_entreprise_AMENAGER)
                 FROM POINT_DE_RECHARGE) AS nb_amenageurs,

                (SELECT COUNT(DISTINCT c.dep_code)
                 FROM STATION s
                 JOIN COMMUNE c
                    ON c.code_insee = s.code_insee
                ) AS nb_departements
        ";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function getPdcByYear() : array {
        $sql = "
            SELECT
                YEAR(s.date_mise_en_service) AS annee,
                COUNT(p.id) AS nb_pdc
            FROM STATION s
            JOIN POINT_DE_RECHARGE p
                ON p.id_station = s.id_station
            WHERE s.date_mise_en_service IS NOT NULL
            GROUP BY YEAR(s.date_mise_en_service)
            ORDER BY annee
        ";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPdcByDepartment() : array {
        $sql = "
            SELECT 
                c.dep_code, 
                COUNT(p.id) AS nb_pdc
            FROM POINT_DE_RECHARGE p
            JOIN STATION s ON p.id_station = s.id_station
            JOIN COMMUNE c ON s.code_insee = c.code_insee
            GROUP BY c.dep_code
            ORDER BY c.dep_code
        ";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPdcByYearAndDepartment() : array {
        $sql = "
            SELECT 
                YEAR(s.date_mise_en_service) AS annee, 
                c.dep_code, 
                COUNT(p.id) AS nb_pdc
            FROM POINT_DE_RECHARGE p
            JOIN STATION s ON p.id_station = s.id_station
            JOIN COMMUNE c ON s.code_insee = c.code_insee
            WHERE s.date_mise_en_service IS NOT NULL
            GROUP BY YEAR(s.date_mise_en_service), c.dep_code
            ORDER BY annee DESC, c.dep_code ASC
        ";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


}

?>