<?php

declare(strict_types=1);


class ImplantationManager {
    private PDO $pdo;
    private array $cache = [];

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findOrCreate(?string $libelle) : int {
        $libelle = !empty($libelle) ? trim($libelle) : "Inconnue";

        if (isset($this->cache[$libelle])) {
            return $this->cache[$libelle];
        }

        // Vérification en BDD
        $stmt = $this->pdo->prepare("SELECT id_implantation FROM IMPLANTATION WHERE implantation = :libelle");
        $stmt->execute([':libelle' => $libelle]);
        $id = $stmt->fetchColumn();

        if (!$id) {
            $stmt = $this->pdo->prepare("INSERT INTO IMPLANTATION (implantation) VALUES (:libelle)");
            $stmt->execute([':libelle' => $libelle]);
            $id = (int)$this->pdo->lastInsertId();
        }

        $this->cache[$libelle] = (int)$id;
        return (int)$id;
    }
}

?>