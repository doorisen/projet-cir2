<?php

declare(strict_types=1);


class StationManager {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create(array $data) : int {
        $sql = "INSERT INTO STATION (id_station_local, id_station_itinerance, nom_station, adresse_station, horaires, date_mise_en_service, longitude, latitude, code_insee, id_implantation, id_condition_acces, id_raccordement) 
                VALUES (:id_local, :id_itin, :nom, :adresse, :horaires, :date_service, :lng, :lat, :insee, :id_impl, :id_access, :id_racc)";
        
        // Formater la date Y-m-d ou mettre null
        $date = null;
        if (!empty($data['date_mise_en_service'])) {
            $d = DateTime::createFromFormat('d/m/Y', $data['date_mise_en_service']);
            if (!$d) {
                $d = DateTime::createFromFormat('Y-m-d', $data['date_mise_en_service']);
            }
            $date = $d ? $d->format('Y-m-d') : null;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_local'     => !empty($data['id_station_local']) ? $data['id_station_local'] : null,
            ':id_itin'      => !empty($data['id_station_itinerance']) ? $data['id_station_itinerance'] : null,
            ':nom'          => trim($data['nom_station']),
            ':adresse'      => trim($data['adresse_station']),
            ':horaires'     => !empty($data['horaires']) ? trim($data['horaires']) : null,
            ':date_service' => $date,
            ':lng'          => (float)$data['longitude'],
            ':lat'          => (float)$data['latitude'],
            ':insee'        => $data['code_insee'],
            ':id_impl'      => (int)$data['id_implantation'],
            ':id_access'    => (int)$data['id_condition_acces'],
            ':id_racc'      => (int)$data['id_raccordement']
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    // Gère la table pivot COMMERCIALISER du MCD
    public function linkEnseigne(int $idStation, int $idEnseigne) : bool {
        $sql = "INSERT IGNORE INTO COMMERCIALISER (id_enseigne, id_station) VALUES (:id_ens, :id_stat)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id_ens' => $idEnseigne, ':id_stat' => $idStation]);
    }

    public function getById(int $id) : ?array {
        $sql = "SELECT s.*, c.nom_standard, i.implantation, ca.condition_acces, r.raccordement 
                FROM STATION s
                JOIN COMMUNE c ON s.code_insee = c.code_insee
                JOIN IMPLANTATION i ON s.id_implantation = i.id_implantation
                JOIN CONDITION_ACCES ca ON s.id_condition_acces = ca.id_condition_acces
                JOIN RACCORDEMENT r ON s.id_raccordement = r.id_raccordement
                WHERE s.id_station = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    public function getForMap(?int $annee = null, ?string $departement = null) : array {
        $sql = "
            SELECT
                s.id_station,
                s.nom_station,
                s.adresse_station,
                s.latitude,
                s.longitude,
                c.nom_standard AS nom_commune,
                c.dep_code,
                COUNT(p.id) AS nb_points
            FROM STATION s
            JOIN COMMUNE c
                ON c.code_insee = s.code_insee
            LEFT JOIN POINT_DE_RECHARGE p
                ON p.id_station = s.id_station
            WHERE 1 = 1
        ";

        $params = [];
        
        if ($annee !== null) {
            $sql .= " AND YEAR(s.date_mise_en_service) = :annee";
            $params[':annee'] = $annee;
        }
        if ($departement !== null) {
            $sql .= " AND c.dep_code = :departement";
            $params[':departement'] = $departement;
        }

        $sql .= "
            GROUP BY s.id_station
            ORDER BY s.nom_station
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}

?>