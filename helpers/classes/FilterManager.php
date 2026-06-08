<?php

declare(strict_types=1);



class FilterManager {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAmenageurs() : array {
        return $this->pdo->query("
            SELECT DISTINCT nom_entreprise_AMENAGER
            FROM POINT_DE_RECHARGE
            WHERE nom_entreprise_AMENAGER IS NOT NULL
            ORDER BY nom_entreprise_AMENAGER
        ")->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getOperateurs() : array {
        return $this->pdo->query("
            SELECT DISTINCT nom_entreprise
            FROM POINT_DE_RECHARGE
            WHERE nom_entreprise IS NOT NULL
            ORDER BY nom_entreprise
        ")->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getDepartements() : array {
        return $this->pdo->query("
            SELECT DISTINCT c.dep_code
            FROM STATION s
            JOIN COMMUNE c
                ON c.code_insee = s.code_insee
            ORDER BY c.dep_code
        ")->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAnnees() : array {
        return $this->pdo->query("
            SELECT DISTINCT YEAR(date_mise_en_service) AS annee
            FROM STATION
            WHERE date_mise_en_service IS NOT NULL
            ORDER BY annee DESC
        ")->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getPrises() : array {
        return [
            ['value' => 'ef',      'label' => 'EF'],
            ['value' => 'type2',   'label' => 'Type 2'],
            ['value' => 'ccs',     'label' => 'Combo CCS'],
            ['value' => 'chademo', 'label' => 'CHAdeMO'],
            ['value' => 'autre',   'label' => 'Autre']
        ];
    }

    public function getStations() : array {
        $stmt = $this->pdo->query("
            SELECT
                id_station AS id,
                nom_station AS nom
            FROM STATION
            WHERE nom_station IS NOT NULL
            ORDER BY nom_station
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}

?>