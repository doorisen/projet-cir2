<?php

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

// Augmentation des limites
set_time_limit(30); 
ini_set('memory_limit', '512M');

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../helpers/classes/CommuneManager.php';
require_once __DIR__ . '/../helpers/classes/EntrepriseManager.php';
require_once __DIR__ . '/../helpers/classes/ContactManager.php';
require_once __DIR__ . '/../helpers/classes/ImplantationManager.php';
require_once __DIR__ . '/../helpers/classes/ConditionAccesManager.php';
require_once __DIR__ . '/../helpers/classes/RaccordementManager.php';
require_once __DIR__ . '/../helpers/classes/EnseigneManager.php';
require_once __DIR__ . '/../helpers/classes/StationManager.php';
require_once __DIR__ . '/../helpers/classes/PdcManager.php';




$communeManager        = new CommuneManager($pdo);
$entrepriseManager    = new EntrepriseManager($pdo);
$contactManager       = new ContactManager($pdo);
$implantationManager  = new ImplantationManager($pdo);
$conditionAccesManager = new ConditionAccesManager($pdo);
$raccordementManager   = new RaccordementManager($pdo);
$enseigneManager       = new EnseigneManager($pdo);
$stationManager        = new StationManager($pdo);
$pdcManager            = new PdcManager($pdo);


function cleanBool($value) : int {
    $v = strtolower(trim($value));
    if ($v === '1' || $v === 'true' || $v === 'oui' || $v === 'o' || $v === 'yes') {
        return 1;
    }
    return 0;
}

function cleanDate($value) : ?string {
    if ($value === null) return null;
    
    $v = trim($value);
    
    // Champ vide ou contient zéros de masquage
    if ($v === '' || $v === '00/00/0000' || $v === '0000-00-00') {
        return null;
    }

    // Date au format français DD/MM/YYYY
    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $v)) {
        $d = DateTime::createFromFormat('d/m/Y', $v);
        return ($d && $d->format('Y') > 1900) ? $d->format('Y-m-d') : null;
    }

    // Date déjà au format ISO YYYY-MM-DD
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) {
        $d = DateTime::createFromFormat('Y-m-d', $v);
        return ($d && $d->format('Y') > 1900) ? $d->format('Y-m-d') : null;
    }

    // Fallback de secours (tente d'interpréter le texte)
    $timestamp = strtotime($v);
    if ($timestamp !== false) {
        $year = date('Y', $timestamp);
        if ($year > 1900 && $year < 2100) {
            return date('Y-m-d', $timestamp);
        }
    }

    return null;
}

function cleanCoord($value) : float {
    return -abs((float)$value);
}


try {

    $response = [
        "populated" => FALSE,
    ];

    // ------------------------------------- Communes -------------------------------------
    $communesFile = __DIR__ . '/communes.csv';
    if (!file_exists($communesFile)) {
        throw new Exception("Le fichier 'communes.csv' est introuvable dans le dossier config/.");
    }

    $handleCommunes = fopen($communesFile, "r");
    
    // En-tête
    $headersCommunes = fgetcsv($handleCommunes, 1000, ";");
    $headersCommunes = array_map('trim', $headersCommunes);

    $pdo->beginTransaction();

    while (($row = fgetcsv($handleCommunes, 1000, ";")) !== FALSE) {
        $data = array_combine($headersCommunes, $row);
        
        $communeManager->create([
            'code_insee'    => $data['code_insee'],
            'nom_standard'  => $data['nom_standard'],
            'code_postal'   => $data['code_postal'],
            'dep_code'      => $data['dep_code'],
            'dep_nom'       => $data['dep_nom'],
            'reg_code'      => $data['reg_code'],
            'reg_nom'       => $data['reg_nom'],
            'population'    => $data['population']
        ]);
    }
    fclose($handleCommunes);
    $pdo->commit();


    // ------------------------------------- IRVE -------------------------------------
    $irveFile = __DIR__ . '/irve_init.csv';
    if (!file_exists($irveFile)) {
        throw new Exception("Le fichier 'irve_init.csv' est introuvable.");
    }

    $stmtInsee = $pdo->query("SELECT code_insee FROM COMMUNE");
    $existingInsee = $stmtInsee->fetchAll(PDO::FETCH_COLUMN, 0);
    $inseeCache = array_flip($existingInsee);

    $handleIrve = fopen($irveFile, "r");
    
    // En-tête
    $headersIrve = fgetcsv($handleIrve, 2000, ",");
    if (!$headersIrve) {
        throw new Exception("Le fichier 'irve_init.csv' est vide ou corrompu.");
    }
    $headersIrve = array_map('trim', $headersIrve);

    $stationCache = [];
    $totalPdcInseres = 0;

    $pdo->beginTransaction();

    while (($row = fgetcsv($handleIrve, 2000, ",")) !== FALSE) {
        if (empty($row) || !isset($row[0])) continue;

        // Transforme le tableau numérique en tableau associatif
        // Exemple : $row[10] devient $data['nom_station']
        $data = array_combine($headersIrve, $row);

        $inseeStation = trim($data['code_insee_commune'] ?? '');
        
        if (strlen($inseeStation) > 0 && strlen($inseeStation) < 5) {
            $inseeStation = str_pad($inseeStation, 5, '0', STR_PAD_LEFT);
        }
        // --- VÉRIFICATION DE LA COHÉRENCE ---
        if (!isset($inseeCache[$inseeStation])) {
            $response['warnings'][] = [
                'insee' => $inseeStation,
                'nom'   => $data['nom_station'] ?? 'Inconnue'
            ];
            continue; 
        }

        // --- ENTREPRISES ET CONTACTS ---
        $nomAmenageur = $entrepriseManager->findOrCreate($data['nom_amenageur'], $data['siren_amenageur'] ?? null);
        $nomOperateur = $entrepriseManager->findOrCreate($data['nom_operateur'], null);

        if (!empty($data['contact_amenageur'])) {
            $contactManager->create($data['contact_amenageur'], null, $nomAmenageur);
        }
        if (!empty($data['contact_operateur']) || !empty($data['telephone_operateur'])) {
            $contactManager->create($data['contact_operateur'] ?? null, $data['telephone_operateur'] ?? null, $nomOperateur);
        }

        // --- TABLES DICTIONNAIRES ---
        $idEnseigne   = $enseigneManager->findOrCreate($data['nom_enseigne']);
        $idImpl       = $implantationManager->findOrCreate($data['implantation_station']);
        $idCondition  = $conditionAccesManager->findOrCreate($data['condition_acces']);
        $idRacc       = $raccordementManager->findOrCreate($data['raccordement']);

        // --- DÉDOUBLONNAGE ET INSERTION DE LA STATION ---
        // Clé de hachage unique basée sur les identifiants textuels du CSV
        $idLocal = $data['id_station_local'] ?? '';
        $idItin  = $data['id_station_itinerance'] ?? '';
        $stationKey = !empty($idLocal) ? $idLocal : (!empty($idItin) ? $idItin : md5($data['nom_station'] . $data['consolidated_longitude'] . $data['consolidated_latitude']));

        if (isset($stationCache[$stationKey])) {
            $idStation = $stationCache[$stationKey];
        } else {
            $idStation = $stationManager->create([
                'id_station_local'      => !empty($idLocal) ? $idLocal : null,
                'id_station_itinerance' => !empty($idItin) ? $idItin : null,
                'nom_station'           => $data['nom_station'],
                'adresse_station'       => $data['adresse_station'],
                'horaires'              => $data['horaires'] ?? null,
                'date_mise_en_service'  => cleanDate($data['date_mise_en_service']) ?? null,
                'longitude'             => cleanCoord($data['consolidated_longitude']),
                'latitude'              => $data['consolidated_latitude'],
                'code_insee'            => $inseeStation,
                'id_implantation'       => $idImpl,
                'id_condition_acces'    => $idCondition,
                'id_raccordement'       => $idRacc
            ]);
            $stationCache[$stationKey] = $idStation;
        }

        $stationManager->linkEnseigne($idStation, $idEnseigne);

        // --- INSERTION DU POINT DE RECHARGE ---
        $pdcManager->create([
            'id'                      => $data['id'],
            'puissance_nominale'      => $data['puissance_nominale'],
            'tarification'            => $data['tarification'] ?? null,
            'prise_type_ef'           => cleanBool($data['prise_type_ef'] ?? 0),
            'prise_type_2'            => cleanBool($data['prise_type_2'] ?? 0),
            'prise_type_combo_ccs'    => cleanBool($data['prise_type_combo_ccs'] ?? 0),
            'prise_type_chademo'      => cleanBool($data['prise_type_chademo'] ?? 0),
            'prise_type_autre'        => cleanBool($data['prise_type_autre'] ?? 0),
            'gratuit'                 => cleanBool($data['gratuit'] ?? 0),
            'paiement_acte'           => cleanBool($data['paiement_acte'] ?? 0),
            'paiement_cb'             => cleanBool($data['paiement_cb'] ?? 0),
            'paiement_autre'          => cleanBool($data['paiement_autre'] ?? 0),
            'cable_t2_attache'        => cleanBool($data['cable_t2_attache'] ?? 0),
            'id_station'              => $idStation,
            'nom_entreprise'          => $nomOperateur,
            'nom_entreprise_AMENAGER' => $nomAmenageur
        ]);
        $totalPdcInseres++;
    }

    fclose($handleIrve);
    $pdo->commit();

    $response['populated'] = TRUE;
    $response['stations_created'] = count($stationCache);
    $response['pdc_created'] = $totalPdcInseres;

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $response = [
        "populated"   => FALSE,
        "error"     => $e->getMessage()
    ];

} finally {
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}


?>