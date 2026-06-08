<?php

declare(strict_types=1);


class ConditionAccesManager {
    private PDO $pdo;
    private array $cache = [];

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findOrCreate(?string $libelle) : int {
        $libelle = !empty($libelle) ? trim($libelle) : "Non spécifiée";

        if (isset($this->cache[$libelle])) {
            return $this->cache[$libelle];
        }

        $stmt = $this->pdo->prepare("SELECT id_condition_acces FROM CONDITION_ACCES WHERE condition_acces = :libelle");
        $stmt->execute([':libelle' => $libelle]);
        $id = $stmt->fetchColumn();

        if (!$id) {
            $stmt = $this->pdo->prepare("INSERT INTO CONDITION_ACCES (condition_acces) VALUES (:libelle)");
            $stmt->execute([':libelle' => $libelle]);
            $id = (int)$this->pdo->lastInsertId();
        }

        $this->cache[$libelle] = (int)$id;
        return (int)$id;
    }
}

?>