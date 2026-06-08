<?php

declare(strict_types=1);

require_once 'PdcManager.php';
require_once 'ApiResponse.php';


class PdcController {
    private PdcManager $manager;

    public function __construct(PDO $pdo) {
        $this->manager = new PdcManager($pdo);
    }

    public function handleRequest(string $method, ?string $idParam) : void {
        $id = $idParam !== null ? (int)$idParam : null;

        try {
            switch ($method) {
                case 'GET':
                    $this->get($id);
                    break;
                case 'POST':
                    $this->create();
                    break;
                case 'PUT':
                    $this->update($id);
                    break;
                case 'DELETE':
                    $this->delete($id);
                    break;
                default:
                    ApiResponse::error("Méthode $method non autorisée.", 405);
            }
        } catch (Throwable $e) {
            ApiResponse::error('Une erreur interne est survenue.', 500);
        }
    }


    private function get(?int $id) : void {
        if ($id === null || $id <= 0) {
            ApiResponse::error('Identifiant manquant ou invalide.', 400);
        }

        $pdc = $this->manager->getById($id);
        if (!$pdc) {
            ApiResponse::error('Point de recharge introuvable.', 404);
        }

        ApiResponse::send($pdc);
    }

    private function create() : void {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !isset($data['id_station'])) {
            ApiResponse::error('Données JSON invalides ou champ obligatoire (id_station) manquant.', 400);
        }
        if (empty($data['id'])) {
            $data['id'] = $this->manager->getNextId();
        }
        if ($this->manager->getById((int)$data['id'])) {
            ApiResponse::error("Un point de recharge avec l'ID {$data['id']} existe déjà.", 409);
        }

        $success = $this->manager->create($data);
        if ($success) {
            ApiResponse::send(['message' => 'Point de recharge créé avec succès.'], 201);
        } else {
            ApiResponse::error('Échec lors de la création en base de données.', 500);
        }
    }

    private function update(?int $id) : void {
        if ($id === null || $id <= 0) {
            ApiResponse::error('Identifiant requis pour la mise à jour.', 400);
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            ApiResponse::error('Données JSON invalides ou corps de requête vide.', 400);
        }

        $existingPdc = $this->manager->getById($id);
        if (!$existingPdc) {
            ApiResponse::error('Point de recharge introuvable. Impossible de le mettre à jour.', 404);
        }

        $mergedData = array_merge($existingPdc, $data);
        $success = $this->manager->update($id, $mergedData);
        if ($success) {
            ApiResponse::send(['message' => 'Point de recharge mis à jour avec succès.']);
        } else {
            ApiResponse::error('Échec lors de la mise à jour en base de données.', 500);
        }
    }

    private function delete(?int $id) : void {
        if ($id === null || $id <= 0) {
            ApiResponse::error('Identifiant requis pour la suppression.', 400);
        }
        
        $existingPdc = $this->manager->getById($id);
        if (!$existingPdc) {
            ApiResponse::error('Point de recharge introuvable. Il a peut-être déjà été supprimé.', 404);
        }

        $success = $this->manager->delete($id);
        if ($success) {
            ApiResponse::send(['message' => 'Point de recharge supprimé avec succès.']);
        } else {
            ApiResponse::error('Échec lors de la suppression en base de données.', 500);
        }
    }


}

?>