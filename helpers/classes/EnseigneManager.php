<?php

declare(strict_types=1);


class EnseigneManager {
    private PDO $pdo;
    private array $cache = [];

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findOrCreate(?string $nom) : int {
        $nom = !empty($nom) ? trim($nom) : "Sans enseigne";

        if (isset($this->cache[$nom])) {
            return $this->cache[$nom];
        }

        $stmt = $this->pdo->prepare("SELECT id_enseigne FROM ENSEIGNE WHERE nom_enseigne = :nom");
        $stmt->execute([':nom' => $nom]);
        $id = $stmt->fetchColumn();

        if (!$id) {
            $stmt = $this->pdo->prepare("INSERT INTO ENSEIGNE (nom_enseigne) VALUES (:nom)");
            $stmt->execute([':nom' => $nom]);
            $id = (int)$this->pdo->lastInsertId();
        }

        $this->cache[$nom] = (int)$id;
        return (int)$id;
    }
}

?>