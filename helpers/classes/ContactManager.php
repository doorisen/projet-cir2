<?php

declare(strict_types=1);


class ContactManager {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create(?string $email, ?string $telephone, string $nomEntreprise) : int {
        $sql = "INSERT INTO CONTACT (email, telephone, nom_entreprise) VALUES (:email, :tel, :entreprise)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':email'      => !empty($email) ? trim($email) : null,
            ':tel'        => !empty($telephone) ? trim($telephone) : null,
            ':entreprise' => $nomEntreprise
        ]);
        return (int)$this->pdo->lastInsertId();
    }
}

?>