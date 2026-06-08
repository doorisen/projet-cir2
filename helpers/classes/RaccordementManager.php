<?php

declare(strict_types=1);


class RaccordementManager {
    private PDO $pdo;
    private array $cache = [];

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findOrCreate(?string $libelle) : int {
        $libelle = !empty($libelle) ? trim($libelle) : "Standard";

        if (isset($this->cache[$libelle])) {
            return $this->cache[$libelle];
        }

        $stmt = $this->pdo->prepare("SELECT id_raccordement FROM RACCORDEMENT WHERE raccordement = :libelle");
        $stmt->execute([':libelle' => $libelle]);
        $id = $stmt->fetchColumn();

        if (!$id) {
            $stmt = $this->pdo->prepare("INSERT INTO RACCORDEMENT (raccordement) VALUES (:libelle)");
            $stmt->execute([':libelle' => $libelle]);
            $id = (int)$this->pdo->lastInsertId();
        }

        $this->cache[$libelle] = (int)$id;
        return (int)$id;
    }
}

?>