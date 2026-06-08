<?php

declare(strict_types=1);


class EntrepriseManager {
    private PDO $pdo;
    private array $cache = []; // Cache mémoire pour éviter les SELECT répétitifs

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findOrCreate(string $nom, ?string $siren) : string {
        $nom = !empty($nom) ? trim($nom) : "Inconnue";

        if (isset($this->cache[$nom])) {
            return $this->cache[$nom];
        }

        // Nettoyage du SIREN si vide ou invalide
        $sirenClean = (!empty($siren) && strlen(trim($siren)) <= 9) ? trim($siren) : null;

        $sql = "INSERT IGNORE INTO ENTREPRISE (nom_entreprise, siren_entreprise) VALUES (:nom, :siren)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nom' => $nom, ':siren' => $sirenClean]);

        $this->cache[$nom] = $nom;
        return $nom;
    }
}

?>