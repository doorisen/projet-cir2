<?php

declare(strict_types=1);


class CommuneManager {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create(array $data) : bool {
        $sql = "INSERT INTO COMMUNE (code_insee, nom_standard, code_postal, dep_code, dep_nom, reg_code, reg_nom, population) 
                VALUES (:insee, :nom, :cp, :dep_code, :nom_dep, :code_reg, :nom_reg, :pop)
                ON DUPLICATE KEY UPDATE nom_standard = :nom_doublon"; // Évite les crashs si doublon dans communes.csv
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':insee'        => $data['code_insee'],
            ':nom'          => $data['nom_standard'],
            ':cp'           => $data['code_postal'],
            ':dep_code'     => $data['dep_code'],
            ':nom_dep'      => $data['dep_nom'],
            ':code_reg'     => $data['reg_code'],
            ':nom_reg'      => $data['reg_nom'],
            ':pop'          => (int)$data['population'],
            ':nom_doublon'  => $data['nom_standard']
        ]);
    }

    public function getByInsee(string $codeInsee) : ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM COMMUNE WHERE code_insee = :insee");
        $stmt->execute([':insee' => $codeInsee]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }
}

?>