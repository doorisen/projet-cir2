<?php

declare(strict_types=1);


class PdcManager {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create(array $data) : bool {
        $sql = "INSERT INTO POINT_DE_RECHARGE (id, puissance_nominale, tarification, prise_type_ef, prise_type_2, prise_type_combo_ccs, prise_type_chademo, prise_type_autre, gratuit, paiement_acte, paiement_cb, paiement_autre, cable_t2_attache, id_station, nom_entreprise, nom_entreprise_AMENAGER) 
                VALUES (:id, :puissance, :tarification, :ef, :t2, :ccs, :chademo, :autre, :gratuit, :acte, :cb, :p_autre, :t2_attache, :id_station, :operateur, :amenageur)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'           => (int)$data['id'],
            ':puissance'    => (float)$data['puissance_nominale'],
            ':tarification' => !empty($data['tarification']) ? trim($data['tarification']) : null,
            ':ef'           => (int)$data['prise_type_ef'],
            ':t2'           => (int)$data['prise_type_2'],
            ':ccs'          => (int)$data['prise_type_combo_ccs'],
            ':chademo'      => (int)$data['prise_type_chademo'],
            ':autre'        => (int)$data['prise_type_autre'],
            ':gratuit'      => (int)$data['gratuit'],
            ':acte'         => (int)$data['paiement_acte'],
            ':cb'           => (int)$data['paiement_cb'],
            ':p_autre'      => (int)$data['paiement_autre'],
            ':t2_attache'   => (int)$data['cable_t2_attache'],
            ':id_station'   => (int)$data['id_station'],
            ':operateur'    => $data['nom_entreprise'],
            ':amenageur'    => $data['nom_entreprise_AMENAGER']
        ]);
    }

    public function delete(int $id) : bool {
        $sql = "DELETE FROM POINT_DE_RECHARGE WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function update(int $id, array $data) : bool {
        $sql = "UPDATE POINT_DE_RECHARGE 
                SET puissance_nominale = :puissance, 
                    tarification = :tarification, 
                    prise_type_ef = :ef, 
                    prise_type_2 = :t2, 
                    prise_type_combo_ccs = :ccs, 
                    prise_type_chademo = :chademo, 
                    prise_type_autre = :autre, 
                    gratuit = :gratuit, 
                    paiement_acte = :acte, 
                    paiement_cb = :cb, 
                    paiement_autre = :p_autre, 
                    cable_t2_attache = :t2_attache, 
                    id_station = :id_station, 
                    nom_entreprise = :operateur, 
                    nom_entreprise_AMENAGER = :amenageur
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'           => $id,
            ':puissance'    => (float)$data['puissance_nominale'],
            ':tarification' => !empty($data['tarification']) ? trim($data['tarification']) : null,
            ':ef'           => (int)$data['prise_type_ef'],
            ':t2'           => (int)$data['prise_type_2'],
            ':ccs'          => (int)$data['prise_type_combo_ccs'],
            ':chademo'      => (int)$data['prise_type_chademo'],
            ':autre'        => (int)$data['prise_type_autre'],
            ':gratuit'      => (int)$data['gratuit'],
            ':acte'         => (int)$data['paiement_acte'],
            ':cb'           => (int)$data['paiement_cb'],
            ':p_autre'      => (int)$data['paiement_autre'],
            ':t2_attache'   => (int)$data['cable_t2_attache'],
            ':id_station'   => (int)$data['id_station'],
            ':operateur'    => $data['nom_entreprise'],
            ':amenageur'    => $data['nom_entreprise_AMENAGER']
        ]);
    }

    public function search(?string $amenageur = null, ?string $prise = null, ?string $departement = null, ?string $id_station = null) : array {
        $sql = "
            SELECT
                p.id,
                p.puissance_nominale,
                s.id_station,
                s.date_mise_en_service,
                s.nom_station,
                c.nom_standard AS commune,
                c.dep_code,
                GROUP_CONCAT(
                    DISTINCT e.nom_enseigne
                    ORDER BY e.nom_enseigne
                    SEPARATOR ', '
                ) AS enseigne,
                p.prise_type_ef,
                p.prise_type_2,
                p.prise_type_combo_ccs,
                p.prise_type_chademo,
                p.prise_type_autre,
                p.nom_entreprise_AMENAGER
            FROM POINT_DE_RECHARGE p
            JOIN STATION s
                ON s.id_station = p.id_station
            JOIN COMMUNE c
                ON c.code_insee = s.code_insee
            LEFT JOIN COMMERCIALISER co
                ON co.id_station = s.id_station
            LEFT JOIN ENSEIGNE e
                ON e.id_enseigne = co.id_enseigne
            WHERE 1=1
        ";

        $params = [];

        if ($amenageur) {
            $sql .= " AND p.nom_entreprise_AMENAGER = :amenageur";
            $params[':amenageur'] = $amenageur;
        }

        if ($departement) {
            $sql .= " AND c.dep_code = :dep";
            $params[':dep'] = $departement;
        }

        if ($id_station) {
            $sql .= " AND s.id_station = :id_station";
            $params[':id_station'] = $id_station;
        }

        $priseColumns = [
            'ef'      => 'prise_type_ef',
            'type2'   => 'prise_type_2',
            'ccs'     => 'prise_type_combo_ccs',
            'chademo' => 'prise_type_chademo',
            'autre'   => 'prise_type_autre'
        ];
        if ($prise && isset($priseColumns[$prise])) {
            $sql .= " AND p.{$priseColumns[$prise]} = 1";
        }

        $sql .= "
            GROUP BY p.id
            ORDER BY s.date_mise_en_service DESC
            LIMIT 500
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id) : ?array {
        $sql = "
            SELECT
                p.*,
                s.nom_station,
                s.adresse_station,
                s.horaires,
                s.date_mise_en_service,
                c.nom_standard AS commune,
                c.dep_code,
                c.code_postal,
                r.raccordement
            FROM POINT_DE_RECHARGE p
            JOIN STATION s
                ON s.id_station = p.id_station
            JOIN COMMUNE c
                ON c.code_insee = s.code_insee
            JOIN RACCORDEMENT r
                ON r.id_raccordement = s.id_raccordement
                WHERE p.id = :id
            ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getNextId() : int {
        $sql = "SELECT MAX(id) + 1 AS next_id FROM POINT_DE_RECHARGE";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['next_id'] ?? 1);
    }


}

?>