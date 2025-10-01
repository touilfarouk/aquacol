<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="application.title">Application - Concession Aquaculture</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
  
    <style>
        :root {
            --primary-color: #4a8eff;
            --secondary-color: #4a8eff;
            --success-color: #4a8eff;
            --warning-color: #4a8eff;
            --danger-color: #4a8eff;
            --title-color:rgb(33, 165, 6);
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 10px 15px -3px rgba(0, 0, 0, 0.2);
        }
        
        .main-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .application-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .info-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: white;
            border-radius: 6px;
            border-left: 4px solid var(--primary-color);
        }
        
        .info-item:last-child {
            margin-bottom: 0;
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .application-form {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 2rem;
        }
        
        .form-section {
            margin-bottom: 2rem;
        }
        
        .form-section h5 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #3a7eff 0%, #3a7eff 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-1px);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            padding: 1rem 1.5rem;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-approved {
            background: #d1edff;
            color: #0c5460;
        }
        
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="index.html">
                <i class="fas fa-fish me-2"></i>
                <span data-i18n="nav.brand">Gestion des Concessions Aquaculture</span>
            </a>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-sm btn-outline-light me-3" href="index.html">
                    <i class="fas fa-map me-1"></i><span data-i18n="nav.map">Carte</span>
                </a>
                <button type="button" id="lang-fr" class="btn btn-sm btn-outline-light" onclick="setLang('fr')">Français</button>
                <button type="button" id="lang-ar" class="btn btn-sm btn-outline-light" onclick="setLang('ar')">العربية</button>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <?php
        // Get concession parameter from URL
        $concessionParam = $_GET['id'] ?? null;
        
        if (!$concessionParam) {
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>ID de concession manquant</div>';
            exit;
        }
        
        // Parse the combined parameter (format: concession-code-numericId or just numericId)
        $concessionCode = null;
        $concessionId = null;
        
        if (strpos($concessionParam, '-') !== false) {
            // New format: concession-code-numericId
            $parts = explode('-', $concessionParam);
            if (count($parts) >= 2) {
                $concessionId = array_pop($parts); // Last part is the numeric ID
                $concessionCode = implode('-', $parts); // Everything else is the code
            }
        } else {
            // Old format: just numeric ID
            $concessionId = $concessionParam;
        }
        
        if (!$concessionId) {
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Format d\'ID invalide</div>';
            exit;
        }
        
        try {
            // Database connection
            require_once('config.php');
            $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ]);
            
            // If we have a concession code, verify both code and ID match
            if ($concessionCode) {
                $stmt = $pdo->prepare("SELECT * FROM coordinates WHERE id = :id AND code_concession = :code");
                $stmt->execute([
                    ':id' => $concessionId,
                    ':code' => $concessionCode
                ]);
            } else {
                // Backward compatibility: just use ID
                $stmt = $pdo->prepare("SELECT * FROM coordinates WHERE id = :id");
                $stmt->execute([':id' => $concessionId]);
            }
            
            $concession = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$concession) {
                if ($concessionCode) {
                    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Concession non trouvée ou code invalide</div>';
                } else {
                    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Concession non trouvée</div>';
                }
                exit;
            }
        ?>
        
        <div class="application-card">
            <div class="card-header">
                <h5><i class="fas fa-file-alt me-3"></i><span data-i18n="application.title">Demande d'Application</span></h5>
                <p class="mb-0"><span data-i18n="application.concession">Concession</span>: <?= htmlspecialchars($concession['nom_zone'] ?? 'Zone ' . $concession['id']) ?></p>
            </div>
            
            <div class="card-body">
                <!-- Concession Information -->

                
                <!-- Application Form (Wizard) -->
                <div class="application-form">
                    <style>
                        .step-icon { width: 40px; height: 40px; border-radius: 50%; background-color: #e9ecef; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-bottom: 0.5rem; }
                        .step-icon.active { background-color: #0d6efd; color: #fff; }
                        .step-icon.completed { background-color: #198754; color: #fff; }
                        .step-connector { flex: 1; height: 2px; background-color: #e9ecef; margin: 0 10px; margin-top: 20px; }
                        .step-connector.completed { background-color: #198754; }
                        .wizard-section { display: none; }
                        .wizard-section.active { display: block; }
                        .required::after { content: " *"; color: #dc3545; }
                    </style>
                    <!-- <h4><i class="fas fa-file-signature me-2"></i><span data-i18n="application.title">Demande d'Octroi de Concession Aquacole</span></h4> -->

                    <!-- Progress Steps -->
                    <div class="progress-steps mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-nowrap">
                            <div class="step-container d-flex flex-column align-items-center">
                                <div class="step-icon active">1</div>
                                <span  class="text-center small fw-medium" data-i18n="wizard.steps.1">Porteur de projet</span>
                            </div>
                            <div class="step-connector"></div>
                            <div class="step-container d-flex flex-column align-items-center">
                                <div class="step-icon">2</div>
                                <span class="text-center small fw-medium" data-i18n="wizard.steps.2">Identification du projet</span>
                            </div>
                            <div class="step-connector"></div>
                            <div class="step-container d-flex flex-column align-items-center">
                                <div class="step-icon">3</div>
                                <span class="text-center small fw-medium" data-i18n="wizard.steps.3">Détails du projet</span>
                            </div>
                            <div class="step-connector"></div>
                            <div class="step-container d-flex flex-column align-items-center">
                                <div class="step-icon">4</div>
                                <span class="text-center small fw-medium" data-i18n="wizard.steps.4">Spécifications techniques</span>
                            </div>
                            <div class="step-connector"></div>
                            <div class="step-container d-flex flex-column align-items-center">
                                <div class="step-icon">5</div>
                                <span class="text-center small fw-medium" data-i18n="wizard.steps.5">Documents</span>
                            </div>
                        </div>
                    </div>

                    <form id="applicationWizardForm" method="POST" action="process_application.php">
                        <input type="hidden" name="concession_id" value="<?= htmlspecialchars($concessionId) ?>">

                        <!-- Step 1: Porteur de projet -->
                        <div class="wizard-section active" data-step="1">
                            <!-- <h5 style="color: var(--title-color);" class="mb-3">1. Porteur de projet</h5> -->
                            <div class="mb-3 d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="porteur_type" id="porteurPhysique" value="physique" required>
                                    <label class="form-check-label" for="porteurPhysique" data-i18n="wizard.porteur.physique">Personne Physique</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="porteur_type" id="porteurMorale" value="morale" required>
                                    <label class="form-check-label" for="porteurMorale" data-i18n="wizard.porteur.morale">Personne Morale</label>
                                </div>
                            </div>

                            <div id="sectionPhysique" class="mt-3 person-section">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label required" data-i18n="wizard.labels.civilite">Civilité</label>
                                        <select class="form-select" name="civilite">
                                            <option value="">Sélectionner</option>
                                            <option>M.</option>
                                            <option>Mme</option>
                                            <option>Mlle</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required" data-i18n="wizard.labels.nom">Nom</label>
                                        <input type="text" class="form-control" name="nom">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required" data-i18n="wizard.labels.prenom">Prénom</label>
                                        <input type="text" class="form-control" name="prenom">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label required" data-i18n="wizard.labels.date_naissance">Date de naissance</label>
                                        <input type="date" class="form-control" name="date_naissance">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required" data-i18n="wizard.labels.lieu_naissance">Lieu de naissance</label>
                                        <input type="text" class="form-control" name="lieu_naissance">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required" data-i18n="wizard.labels.adresse">Adresse</label>
                                    <input type="text" class="form-control" name="adresse">
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label required" data-i18n="wizard.labels.code_postal">Code postal</label>
                                        <input type="text" class="form-control" name="code_postal">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required" data-i18n="wizard.labels.ville">Ville</label>
                                        <input type="text" class="form-control" name="ville">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required" data-i18n="wizard.labels.pays">Pays</label>
                                        <input type="text" class="form-control" name="pays" value="Algérie" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label required" data-i18n="wizard.labels.telephone">Téléphone</label>
                                        <input type="tel" class="form-control" name="telephone">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required" data-i18n="wizard.labels.email">Email</label>
                                        <input type="email" class="form-control" name="email">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required" data-i18n="wizard.labels.num_identite">Numéro de pièce d'identité</label>
                                    <input type="text" class="form-control" name="num_identite">
                                </div>
                            </div>

                            <!-- Personne Morale -->
                            <div id="sectionMorale" class="mt-3 person-section" style="display:none">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required" data-i18n="wizard.morale.raison_sociale">Raison sociale</label>
                                    <input type="text" class="form-control" name="raison_sociale">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required" data-i18n="wizard.morale.forme_juridique">Forme juridique</label>
                                    <select class="form-select" name="forme_juridique">
                                        <option value="">Sélectionner</option>
                                        <option value="SARL">SARL</option>
                                        <option value="EURL">EURL</option>
                                        <option value="SA">SA</option>
                                        <option value="SNC">SNC</option>
                                        <option value="SCS">SCS</option>
                                        <option value="SCA">SCA</option>
                                        <option value="GIE">GIE</option>
                                        <option value="Coopérative">Coopérative</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required" data-i18n="wizard.morale.registre_commerce">N° Registre du Commerce</label>
                                    <input type="text" class="form-control" name="registre_commerce">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required" data-i18n="wizard.morale.nif">NIF</label>
                                    <input type="text" class="form-control" name="nif">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required" data-i18n="wizard.morale.adresse_siege">Adresse du siège social</label>
                                <input type="text" class="form-control" name="adresse_siege">
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label required">Code postal</label>
                                    <input type="text" class="form-control" name="code_postal_siege">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Ville</label>
                                    <input type="text" class="form-control" name="ville_siege">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Pays</label>
                                    <input type="text" class="form-control" name="pays_siege" value="Algérie" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Téléphone</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+213</span>
                                        <input type="tel" class="form-control" name="telephone_siege">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Email</label>
                                    <input type="email" class="form-control" name="email_siege">
                                </div>
                            </div>
                            <hr class="my-3">
                            <h6 class="mb-3" data-i18n="wizard.morale.representant_legal">Représentant légal</h6>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label required">Civilité</label>
                                    <select class="form-select" name="civilite_representant">
                                        <option value="">Sélectionner</option>
                                        <option>M.</option>
                                        <option>Mme</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Nom</label>
                                    <input type="text" class="form-control" name="nom_representant">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Prénom</label>
                                    <input type="text" class="form-control" name="prenom_representant">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required" data-i18n="wizard.morale.fonction">Fonction</label>
                                    <input type="text" class="form-control" name="fonction_representant">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required" data-i18n="wizard.morale.email_representant">Email du représentant</label>
                                    <input type="email" class="form-control" name="email_representant">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required" data-i18n="wizard.morale.telephone_representant">Téléphone du représentant</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+213</span>
                                        <input type="tel" class="form-control" name="telephone_representant">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" data-i18n="wizard.morale.capital_social">Capital social (en DZD)</label>
                                    <input type="number" class="form-control" name="capital_social" min="0" step="1000">
                                </div>
                            </div>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" class="btn btn-primary" onclick="goToStep(2)" data-i18n="actions.next">Suivant</button>
                            </div>
                        </div>

                        <!-- Step 2: Identification du projet -->
                        <div class="wizard-section" data-step="2">
                        <!-- <h5 style="color: var(--title-color);" class="mb-3">2. Identification du projet</h5> -->
                      
          <!-- Espèces d'élevage -->
          <div class="bg-blue-50 p-4 rounded-lg">
                            <h6 class="text-primary mb-2">Informations de base</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="denominationProjet" class="form-label required">Dénomination du projet</label>
                                    <input type="text" class="form-control" id="denominationProjet" name="denomination_projet" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dateDemande" class="form-label required">Date de la demande</label>
                                    <input type="date" class="form-control" id="dateDemande" name="date_demande" required>
                                </div>
                            </div>
                        </div>
                            
                            <!-- Localisation du projet -->
                            <div class="bg-blue-50 p-3 rounded mb-3">
                                <h6 class="text-primary mb-3">Localisation du projet</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="wilayaProjet" class="form-label required">Wilaya</label>
                                        <select class="form-select" id="wilayaProjet" name="wilaya_projet" required>
                                            <option value="">Sélectionner une wilaya</option>
                                            <option value="alger">Alger</option>
                                            <option value="oran">Oran</option>
                                            <option value="constantine">Constantine</option>
                                            <option value="annaba">Annaba</option>
                                            <option value="bejaia">Béjaïa</option>
                                            <option value="tlemcen">Tlemcen</option>
                                            <option value="setif">Sétif</option>
                                            <option value="blida">Blida</option>
                                            <option value="tizi_ouzou">Tizi Ouzou</option>
                                            <option value="batna">Batna</option>
                                            <option value="autre">Autre</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="daira" class="form-label required">Daïra</label>
                                        <input type="text" class="form-control" id="daira" name="daira" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="communeProjet" class="form-label required">Commune</label>
                                        <input type="text" class="form-control" id="communeProjet" name="commune_projet" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="zaaLieu" class="form-label">ZAA/Lieu</label>
                                        <input type="text" class="form-control" id="zaaLieu" name="zaa_lieu">
                                    </div>
                                </div>
                                
                                <!-- Superficie demandée -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Superficie demandée</label>
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label small">À terre (m²)</label>
                                                <input type="number" class="form-control" id="superficieTerre" name="superficie_terre" min="0" step="0.01">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small">En mer/plan d'eau (Ha)</label>
                                                <input type="number" class="form-control" id="superficieMer" name="superficie_mer" min="0" step="0.01">
                                            </div>
                                        </div>
                                    </div>
                                       <!-- Coordonnées géographiques -->
                               
                                       <div class="col-md-6 mb-3">
                                        <label class="form-label">Coordonnées géographiques</label>
                                        <div class="row g-2">
                                            <div class="col">
                                                <label class="form-label small">Point A</label>
                                                <input type="text" class="form-control" id="coordA" name="coord_a" placeholder="Lat, Long">
                                            </div>
                                            <div class="col">
                                                <label class="form-label small">Point B</label>
                                                <input type="text" class="form-control" id="coordB" name="coord_b" placeholder="Lat, Long">
                                            </div>
                                            <div class="col">
                                                <label class="form-label small">Point C</label>
                                                <input type="text" class="form-control" id="coordC" name="coord_c" placeholder="Lat, Long">
                                            </div>
                                            <div class="col">
                                                <label class="form-label small">Point D</label>
                                                <input type="text" class="form-control" id="coordD" name="coord_d" placeholder="Lat, Long">
                                            </div>
                                            </div>

                                    </div>
                                </div>
                                
                             
                                
                            </div>

                            <!-- Milieu d'élevage -->
                            <div class="bg-blue-50 p-3 rounded mb-3">
                                <h6 class="text-primary mb-3">Milieu d'élevage</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="milieu_elevage[]" value="eau_mer" id="milieuMer">
                                            <label class="form-check-label" for="milieuMer">
                                                Eau de mer
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="milieu_elevage[]" value="eau_douce" id="milieuDouce">
                                            <label class="form-check-label" for="milieuDouce">
                                                Eau douce
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="milieu_elevage[]" value="eau_saumatre" id="milieuSaumatre">
                                            <label class="form-check-label" for="milieuSaumatre">
                                                Eau saumâtre
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Type d'établissement -->
                            <div class="bg-blue-50 p-3 rounded mb-3">
                                <h6 class="text-primary mb-3">Type d'établissement d'aquaculture</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="type_etablissement[]" value="pisciculture" id="typePisciculture">
                                            <label class="form-check-label" for="typePisciculture">
                                                Établissement de pisciculture
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="type_etablissement[]" value="conchyliculture" id="typeConchyliculture">
                                            <label class="form-check-label" for="typeConchyliculture">
                                                Établissement de conchyliculture
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="type_etablissement[]" value="carcinoculture" id="typeCarcinoculture">
                                            <label class="form-check-label" for="typeCarcinoculture">
                                                Établissement de carcinoculture
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="type_etablissement[]" value="echinoculture" id="typeEchinoculture">
                                            <label class="form-check-label" for="typeEchinoculture">
                                                Établissement d'échinoculture
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="type_etablissement[]" value="algoculture" id="typeAlgoculture">
                                            <label class="form-check-label" for="typeAlgoculture">
                                                Établissement d'algoculture
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="type_etablissement[]" value="prelevement" id="typePrelevement">
                                            <label class="form-check-label" for="typePrelevement">
                                                Établissement de prélèvement de juvéniles ou naissains
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="type_etablissement[]" value="crevetticulture" id="typeCrevette">
                                            <label class="form-check-label" for="typeCrevette">
                                                Établissement de crevetticulture
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="type_etablissement[]" value="coralliculture" id="typeCorail">
                                            <label class="form-check-label" for="typeCorail">
                                                Établissement de coralliculture
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="type_etablissement[]" value="aquariophilie" id="typeAquario">
                                            <label class="form-check-label" for="typeAquario">
                                                Établissement d'aquariophilie
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="type_etablissement[]" value="autre" id="typeAutre">
                                            <label class="form-check-label" for="typeAutre">
                                                Autre (précisez) :
                                            </label>
                                        </div>
                                        <input type="text" class="form-control mt-2" id="typeEtablissementAutre" name="type_etablissement_autre" placeholder="Précisez le type d'établissement">
                                    </div>
                                </div>
                            </div>

                            <!-- Intérêt de l'activité aquacole -->
                            <div class="bg-blue-50 p-3 rounded mb-3">
                                <h6 class="text-primary mb-3">Intérêt de l'activité aquacole</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="interet_activite[]" value="economique" id="interetEconomique">
                                            <label class="form-check-label" for="interetEconomique">
                                                Économique
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="interet_activite[]" value="recherche" id="interetRecherche">
                                            <label class="form-check-label" for="interetRecherche">
                                                Recherche scientifique, développement
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="interet_activite[]" value="autre" id="interetAutre">
                                            <label class="form-check-label" for="interetAutre">
                                                Autres (précisez) :
                                            </label>
                                        </div>
                                        <input type="text" class="form-control mt-2" id="interetAutreTexte" name="interet_autre_texte" placeholder="Précisez l'intérêt">
                                    </div>
                                </div>
                            </div>

                            <!-- Financement du projet -->
                            <div class="bg-blue-50 p-3 rounded mb-3">
                                <h6 class="text-primary mb-3">Financement du projet</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="montantGlobal" class="form-label required">Montant global du projet (DA)</label>
                                        <input type="number" class="form-control" id="montantGlobal" name="montant_global" required min="0">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nombreEmploi" class="form-label">Nombre d'emplois</label>
                                        <input type="number" class="form-control" id="nombreEmploi" name="nombre_emploi" min="0">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dureeRealisation" class="form-label">Durée de réalisation (mois)</label>
                                        <input type="number" class="form-control" id="dureeRealisation" name="duree_realisation" min="0">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="capaciteProduction" class="form-label">Capacité de production (Tonnes/cycle)</label>
                                        <input type="number" class="form-control" id="capaciteProduction" name="capacite_production" min="0" step="0.01">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="goToStep(1)" data-i18n="actions.previous">Précédent</button>
                            <button type="button" class="btn btn-primary" onclick="goToStep(3)" data-i18n="actions.next">Suivant</button>
                        </div>
                        </div>
                        
                    </div>


                        <!-- Step 3: Détails du projet -->
                        <div class="wizard-section" data-step="3">
                            <!-- <h5 style="color: var(--title-color);" class="mb-3">3. Détails du projet</h5> -->
                            <div class="bg-light p-3 rounded mb-3">
                                <!-- <h6 class="text-primary mb-2">Espèces d'élevage</h6> -->
                                <div class="space-y-6">
                            <!-- Espèces d'élevage -->
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-blue-800 mb-4">Espèces d'élevage</h3>
                                
                                <!-- Espèces de pisciculture -->
                                <div class="mb-6">
                                <h4 class="font-medium text-gray-700 mb-3">Espèces des établissements de pisciculture</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesPisciculture" value="daurade" class="form-checkbox">
                                    <span class="ml-2">Daurade royale</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesPisciculture" value="loup" class="form-checkbox">
                                    <span class="ml-2">Loup de mer (Bar)</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesPisciculture" value="maigre" class="form-checkbox">
                                    <span class="ml-2">Maigre</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesPisciculture" value="tilapia" class="form-checkbox">
                                    <span class="ml-2">Tilapia</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesPisciculture" value="poisson_chat" class="form-checkbox">
                                    <span class="ml-2">Poisson chat</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesPisciculture" value="carpes" class="form-checkbox">
                                    <span class="ml-2">Carpes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesPisciculture" value="mulet" class="form-checkbox">
                                    <span class="ml-2">Mulet</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesPisciculture" value="anguille" class="form-checkbox">
                                    <span class="ml-2">Anguille</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesPisciculture" value="sandre" class="form-checkbox">
                                    <span class="ml-2">Sandre</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesPisciculture" value="black_bass" class="form-checkbox">
                                    <span class="ml-2">Black-bass</span>
                                    </label>
                                </div>
                                <div class="mt-3">
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesPisciculture" value="autre" class="form-checkbox">
                                    <span class="ml-2">Autre (précisez) :</span>
                                    </label>
                                    <input type="text" id="especesPiscicultureAutre" class="ml-2 flex-1 px-3 py-2 border rounded-md">
                                </div>
                                </div>

                                <!-- Espèces de conchyliculture -->
                                <div class="mb-6">
                                <h4 class="font-medium text-gray-700 mb-3">Espèces des établissements de conchyliculture</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesConchyliculture" value="moules" class="form-checkbox">
                                    <span class="ml-2">Moules</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesConchyliculture" value="huitres" class="form-checkbox">
                                    <span class="ml-2">Huîtres</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesConchyliculture" value="palourdes" class="form-checkbox">
                                    <span class="ml-2">Palourdes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesConchyliculture" value="coquilles_st_jacques" class="form-checkbox">
                                    <span class="ml-2">Coquilles St-Jacques</span>
                                    </label>
                                </div>
                                <div class="mt-3">
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesConchyliculture" value="autre" class="form-checkbox">
                                    <span class="ml-2">Autre (précisez) :</span>
                                    </label>
                                    <input type="text" id="especesConchylicultureAutre" class="ml-2 flex-1 px-3 py-2 border rounded-md">
                                </div>
                                </div>

                                <!-- Espèces de carcinoculture -->
                                <div class="mb-6">
                                <h4 class="font-medium text-gray-700 mb-3">Espèces des établissements de carcinoculture (crevetticulture)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesCarcinoculture" value="litopenaeus_vannamei" class="form-checkbox">
                                    <span class="ml-2">Litopenaeus vannamei</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesCarcinoculture" value="penaeus_kerathurus" class="form-checkbox">
                                    <span class="ml-2">Penaeus Kerathurus</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesCarcinoculture" value="marsupenaeus_japonicus" class="form-checkbox">
                                    <span class="ml-2">Marsupenaeus japonicus</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesCarcinoculture" value="macrobrachium_rosenbergii" class="form-checkbox">
                                    <span class="ml-2">Macrobrachium rosenbergii</span>
                                    </label>
                                </div>
                                <div class="mt-3">
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesCarcinoculture" value="autre" class="form-checkbox">
                                    <span class="ml-2">Autre (précisez) :</span>
                                    </label>
                                    <input type="text" id="especesCarcinocultureAutre" class="ml-2 flex-1 px-3 py-2 border rounded-md">
                                </div>
                                </div>

                                <!-- Espèces d'échinoculture -->
                                <div class="mb-6">
                                <h4 class="font-medium text-gray-700 mb-3">Espèces des établissements d'échinoculture</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesEchinoculture" value="oursins" class="form-checkbox">
                                    <span class="ml-2">Oursins</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesEchinoculture" value="concombre_mer" class="form-checkbox">
                                    <span class="ml-2">Concombre de mer</span>
                                    </label>
                                </div>
                                <div class="mt-3">
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="especesEchinoculture" value="autre" class="form-checkbox">
                                    <span class="ml-2">Autre (précisez) :</span>
                                    </label>
                                    <input type="text" id="especesEchinocultureAutre" class="ml-2 flex-1 px-3 py-2 border rounded-md">
                                </div>
                                </div>
                            </div>

                            <!-- Phase d'élevage -->
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-green-800 mb-4">Phase d'élevage</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="phaseElevage" value="ecloserie" class="form-checkbox">
                                    <span class="ml-2">Écloserie</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="phaseElevage" value="pre_grossissement" class="form-checkbox">
                                    <span class="ml-2">Pré-grossissement</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="phaseElevage" value="grossissement" class="form-checkbox">
                                    <span class="ml-2">Grossissement</span>
                                </label>
                                </div>
                            </div>

                            <!-- Structures d'élevage -->
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-purple-800 mb-4">Structures d'élevage</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="structuresElevage" value="bassins" class="form-checkbox">
                                    <span class="ml-2">Bassins</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="structuresElevage" value="cages" class="form-checkbox">
                                    <span class="ml-2">Cages</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="structuresElevage" value="filieres" class="form-checkbox">
                                    <span class="ml-2">Filières</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="structuresElevage" value="etangs" class="form-checkbox">
                                    <span class="ml-2">Étangs</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="structuresElevage" value="bacs_raceways" class="form-checkbox">
                                    <span class="ml-2">Bacs / Raceways</span>
                                </label>
                                <div class="flex items-center">
                                    <label class="inline-flex items-center">
                                    <input type="checkbox" name="structuresElevage" value="autres" class="form-checkbox">
                                    <span class="ml-2">Autres :</span>
                                    </label>
                                    <input type="text" id="structuresElevageAutres" class="ml-2 flex-1 px-3 py-2 border rounded-md">
                                </div>
                                </div>
                                
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label for="nombreStructures" class="form-label block text-sm font-medium text-gray-700 mb-1">Nombre de structures</label>
                                    <input type="number" id="nombreStructures" class="w-full px-3 py-2 border rounded-md">
                                </div>
                                <div class="form-group">
                                    <label for="dimensionsStructures" class="form-label block text-sm font-medium text-gray-700 mb-1">Dimensions</label>
                                    <input type="text" id="dimensionsStructures" class="w-full px-3 py-2 border rounded-md" placeholder="ex: 10m x 5m x 2m">
                                </div>
                                </div>
                            </div>
                            </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-outline-secondary" onclick="goToStep(2)" data-i18n="actions.previous">Précédent</button>
                                <button type="button" class="btn btn-primary" onclick="goToStep(4)" data-i18n="actions.next">Suivant</button>
                            </div>
                        </div>

                        <!-- Step 4: Spécifications techniques -->
                        <div class="wizard-section" data-step="4">
                            <!-- <h5 style="color: var(--title-color);" class="mb-3">4. Spécifications techniques</h5> -->
                            <div class="space-y-6">
          <!-- Système de production -->
          <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-blue-800 mb-4">Système de production</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="form-group">
                <label for="systemeProduction" class="form-label block text-sm font-medium text-gray-700 mb-1">Type de système</label>
                <select id="systemeProduction" class="w-full px-3 py-2 border rounded-md">
                  <option value="">Sélectionner un système</option>
                  <option value="intensif">Intensif</option>
                  <option value="semi_intensif">Semi-intensif</option>
                  <option value="extensif">Extensif</option>
                  <option value="super_intensif">Super-intensif</option>
                </select>
              </div>
              <div class="form-group">
                <label for="densiteElevage" class="form-label block text-sm font-medium text-gray-700 mb-1">Densité d'élevage (kg/m³)</label>
                <input type="number" id="densiteElevage" class="w-full px-3 py-2 border rounded-md" step="0.1">
              </div>
            </div>
          </div>

          <!-- Alimentation -->
          <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-green-800 mb-4">Alimentation</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="form-group">
                <label for="typeAliment" class="form-label block text-sm font-medium text-gray-700 mb-1">Type d'aliment</label>
                <select id="typeAliment" class="w-full px-3 py-2 border rounded-md">
                  <option value="">Sélectionner</option>
                  <option value="granules_commerciaux">Granulés commerciaux</option>
                  <option value="aliment_naturel">Aliment naturel</option>
                  <option value="aliment_mixte">Aliment mixte</option>
                  <option value="aliment_vivant">Aliment vivant</option>
                </select>
              </div>
              <div class="form-group">
                <label for="frequenceAlimentation" class="form-label block text-sm font-medium text-gray-700 mb-1">Fréquence d'alimentation</label>
                <select id="frequenceAlimentation" class="w-full px-3 py-2 border rounded-md">
                  <option value="">Sélectionner</option>
                  <option value="1_fois_jour">1 fois par jour</option>
                  <option value="2_fois_jour">2 fois par jour</option>
                  <option value="3_fois_jour">3 fois par jour</option>
                  <option value="continue">Continue</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Gestion de l'eau -->
          <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-purple-800 mb-4">Gestion de l'eau</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="form-group">
                <label for="sourceEau" class="form-label block text-sm font-medium text-gray-700 mb-1">Source d'eau</label>
                <select id="sourceEau" class="w-full px-3 py-2 border rounded-md">
                  <option value="">Sélectionner</option>
                  <option value="mer">Eau de mer</option>
                  <option value="riviere">Eau de rivière</option>
                  <option value="lac">Eau de lac</option>
                  <option value="forage">Eau de forage</option>
                  <option value="reseau">Eau du réseau</option>
                </select>
              </div>
              <div class="form-group">
                <label for="traitementEau" class="form-label block text-sm font-medium text-gray-700 mb-1">Traitement de l'eau</label>
                <select id="traitementEau" class="w-full px-3 py-2 border rounded-md">
                  <option value="">Sélectionner</option>
                  <option value="filtration_mecanique">Filtration mécanique</option>
                  <option value="filtration_biologique">Filtration biologique</option>
                  <option value="ozonation">Ozonation</option>
                  <option value="uv">Stérilisation UV</option>
                  <option value="aucun">Aucun traitement</option>
                </select>
              </div>
              <div class="form-group">
                <label for="renouvellementEau" class="form-label block text-sm font-medium text-gray-700 mb-1">Taux de renouvellement (%/jour)</label>
                <input type="number" id="renouvellementEau" class="w-full px-3 py-2 border rounded-md" step="0.1" min="0" max="100">
              </div>
              <div class="form-group">
                <label for="temperatureEau" class="form-label block text-sm font-medium text-gray-700 mb-1">Température de l'eau (°C)</label>
                <input type="number" id="temperatureEau" class="w-full px-3 py-2 border rounded-md" step="0.1">
              </div>
            </div>
          </div>

          <!-- Équipements -->
          <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-yellow-800 mb-4">Équipements</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-2">
                <label class="inline-flex items-center">
                  <input type="checkbox" name="equipements" value="pompes" class="form-checkbox">
                  <span class="ml-2">Pompes</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="checkbox" name="equipements" value="aerateurs" class="form-checkbox">
                  <span class="ml-2">Aérateurs</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="checkbox" name="equipements" value="filtres" class="form-checkbox">
                  <span class="ml-2">Filtres</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="checkbox" name="equipements" value="generateurs_oxygene" class="form-checkbox">
                  <span class="ml-2">Générateurs d'oxygène</span>
                </label>
              </div>
              <div class="space-y-2">
                <label class="inline-flex items-center">
                  <input type="checkbox" name="equipements" value="systeme_chauffage" class="form-checkbox">
                  <span class="ml-2">Système de chauffage</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="checkbox" name="equipements" value="systeme_refroidissement" class="form-checkbox">
                  <span class="ml-2">Système de refroidissement</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="checkbox" name="equipements" value="distributeurs_aliment" class="form-checkbox">
                  <span class="ml-2">Distributeurs d'aliment</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="checkbox" name="equipements" value="systeme_monitoring" class="form-checkbox">
                  <span class="ml-2">Système de monitoring</span>
                </label>
              </div>
            </div>
          </div>

          <!-- Gestion des déchets -->
          <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-red-800 mb-4">Gestion des déchets</h3>
            <div class="form-group">
              <label for="gestionDechets" class="form-label block text-sm font-medium text-gray-700 mb-1">Méthode de gestion des déchets</label>
              <textarea id="gestionDechets" rows="3" class="w-full px-3 py-2 border rounded-md" placeholder="Décrivez votre méthode de gestion des déchets..."></textarea>
            </div>
          </div>
        </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-outline-secondary" onclick="goToStep(3)" data-i18n="actions.previous">Précédent</button>
                                <button type="button" class="btn btn-primary" onclick="goToStep(5)" data-i18n="actions.next">Suivant</button>
                            </div>
                        </div>

                        <!-- Step 5: Documents -->
                        <div class="wizard-section" data-step="5">
                            <!-- <h5 class="mb-3">5. Documents</h5> -->
                            <div class="space-y-6">
          <!-- Dossier administratif -->
          <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-blue-800 mb-4">1. Dossier administratif</h3>
            <div class="space-y-4">
              <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Copie conforme de la carte nationale d'identité (pour les personnes physiques) <span class="text-red-500">*</span>
                </label>
                <input type="file" name="carteIdentite" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border rounded-md">
                <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, JPG, PNG (max 5MB)</p>
              </div>
              
              <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Statuts et bulletin officiel des annonces légales (pour les personnes morales)
                </label>
                <input type="file" name="statutsSociete" accept=".pdf" class="w-full px-3 py-2 border rounded-md">
                <p class="text-xs text-gray-500 mt-1">Format accepté: PDF (max 10MB)</p>
              </div>
              
              <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Extrait de registre de commerce <span class="text-red-500">*</span>
                </label>
                <input type="file" name="registreCommerce" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border rounded-md">
                <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, JPG, PNG (max 5MB)</p>
              </div>
            </div>
          </div>

          <!-- Dossier technique -->
          <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-green-800 mb-4">2. Dossier technique</h3>
            <div class="space-y-4">
              <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Étude technico-économique <span class="text-red-500">*</span>
                </label>
                <input type="file" name="etudeEconomique" accept=".pdf,.doc,.docx" class="w-full px-3 py-2 border rounded-md">
                <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, DOC, DOCX (max 20MB)</p>
              </div>
              
              <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Résultats d'analyses (eau, sol, etc.)
                </label>
                <input type="file" name="resultatsAnalyses" accept=".pdf,.doc,.docx" class="w-full px-3 py-2 border rounded-md">
                <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, DOC, DOCX (max 10MB)</p>
              </div>
              
              <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Plan de masse <span class="text-red-500">*</span>
                </label>
                <input type="file" name="planMasse" accept=".pdf,.jpg,.jpeg,.png,.dwg" class="w-full px-3 py-2 border rounded-md">
                <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, JPG, PNG, DWG (max 15MB)</p>
              </div>
              
              <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Autres documents techniques
                </label>
                <input type="file" name="autresDocuments" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple class="w-full px-3 py-2 border rounded-md">
                <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, DOC, JPG, PNG (max 5MB par fichier)</p>
              </div>
            </div>
          </div>

          <!-- Déclaration -->
          <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-yellow-800 mb-4">Déclaration sur l'honneur</h3>
            <div class="space-y-4">
              <div class="bg-white p-4 rounded border">
                <p class="text-sm text-gray-700 mb-4">
                  Je soussigné(e), déclare sur l'honneur que les informations fournies dans le présent formulaire 
                  sont exactes et complètes à la date de la présente déclaration. Je m'engage à respecter 
                  la réglementation en vigueur concernant l'aquaculture et l'environnement.
                </p>
                <label class="inline-flex items-center">
                  <input type="checkbox" name="declaration" required class="form-checkbox">
                  <span class="ml-2 text-sm">
                    J'accepte les termes de cette déclaration et je certifie l'exactitude des informations fournies <span class="text-red-500">*</span>
                  </span>
                </label>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                  <label for="lieuSignature" class="form-label required block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                  <input type="text" id="lieuSignature" class="w-full px-3 py-2 border rounded-md" required>
                </div>
                <div class="form-group">
                  <label for="dateSignature" class="form-label required block text-sm font-medium text-gray-700 mb-1">Date</label>
                  <input type="date" id="dateSignature" class="w-full px-3 py-2 border rounded-md" required>
                </div>
              </div>
            </div>
          </div>
        </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-outline-secondary" onclick="goToStep(4)" data-i18n="actions.previous">Précédent</button>
                                <button type="submit" class="btn btn-primary" data-i18n="actions.submit">Soumettre la Demande</button>
                            </div>
                        </div>
                    </form>

                    <script>
        // Toggle between Personne Physique and Personne Morale
        function togglePorteurType() {
            const type = document.querySelector('input[name="porteur_type"]:checked')?.value;
            const phy = document.getElementById('sectionPhysique');
            const mor = document.getElementById('sectionMorale');
            if (!type) return;
            // Show/Hide
            if (type === 'physique') {
                phy.style.display = '';
                mor.style.display = 'none';
                setSectionRequired(phy, true);
                setSectionRequired(mor, false);
            } else {
                phy.style.display = 'none';
                mor.style.display = '';
                setSectionRequired(phy, false);
                setSectionRequired(mor, true);
            }
        }
        function setSectionRequired(container, on) {
            if (!container) return;
            container.querySelectorAll('input, select, textarea').forEach(el => {
                // Only toggle fields that make sense for required
                const shouldRequire = on && el.name && !el.hasAttribute('readonly');
                el.required = shouldRequire;
                if (!shouldRequire) el.classList.remove('is-invalid');
            });
        }
        document.addEventListener('change', function(e){
            if (e.target && e.target.name === 'porteur_type') {
                togglePorteurType();
            }
        });
        // Initialize toggle on load
        document.addEventListener('DOMContentLoaded', function(){
            // Default to physique if none selected
            const radios = document.querySelectorAll('input[name="porteur_type"]');
            const anyChecked = Array.from(radios).some(r => r.checked);
            if (!anyChecked && radios.length) {
                radios[0].checked = true;
            }
            togglePorteurType();
        });
                        function setActiveStep(step) {
                            document.querySelectorAll('.wizard-section').forEach(s => s.classList.remove('active'));
                            const target = document.querySelector(`.wizard-section[data-step="${step}"]`);
                            if (target) target.classList.add('active');
                            // Update step icons
                            const icons = document.querySelectorAll('.step-icon');
                            icons.forEach((icon, idx) => {
                                icon.classList.remove('active', 'completed');
                                const s = idx + 1;
                                if (s < step) icon.classList.add('completed');
                                if (s === step) icon.classList.add('active');
                            });
                        }
                        function goToStep(step) { setActiveStep(step); window.scrollTo({ top: document.querySelector('.application-form').offsetTop - 60, behavior: 'smooth' }); }
                        // init
                        setActiveStep(1);
                    </script>
                </div>
            </div>
        </div>
        
        <?php
        } catch (Exception $e) {
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- i18n loader (reads ?lang=fr|ar and applies langs/*.json) -->
    <script src="langs/i18n.js"></script>
    <script>
        // Language switcher: preserves other query params
        function setLang(lang) {
            const url = new URL(window.location.href);
            url.searchParams.set('lang', lang);
            window.location.href = url.toString();
        }
        // Highlight active language button
        (function highlightActiveLang(){
            const params = new URLSearchParams(window.location.search);
            const lang = params.get('lang') || 'fr';
            const frBtn = document.getElementById('lang-fr');
            const arBtn = document.getElementById('lang-ar');
            if (frBtn && arBtn) {
                if (lang === 'ar') {
                    arBtn.classList.add('btn-light');
                    arBtn.classList.remove('btn-outline-light');
                    frBtn.classList.add('btn-outline-light');
                    frBtn.classList.remove('btn-light');
                } else {
                    frBtn.classList.add('btn-light');
                    frBtn.classList.remove('btn-outline-light');
                    arBtn.classList.add('btn-outline-light');
                    arBtn.classList.remove('btn-light');
                }
            }
        })();
        // Keep current lang when navigating between pages
        document.addEventListener('click', function(e){
            const anchor = e.target.closest('a');
            if (!anchor || !anchor.href) return;
            try {
                const href = anchor.getAttribute('href');
                const url = new URL(href, window.location.href);
                if (/index\.(html|php)$|details\.php|application\.php/i.test(url.pathname)) {
                    const lang = new URLSearchParams(window.location.search).get('lang');
                    if (lang) {
                        url.searchParams.set('lang', lang);
                        anchor.setAttribute('href', url.toString());
                    }
                }
            } catch(_) {}
        });
        // Form validation and submission
        document.getElementById('applicationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                alert('Veuillez remplir tous les champs obligatoires.');
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours...';
            submitBtn.disabled = true;
            
            // Simulate form submission (replace with actual submission logic)
            setTimeout(() => {
                alert('✅ Demande soumise avec succès! Vous recevrez une confirmation par email.');
                
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                // Optionally redirect or reset form
                // window.location.href = 'details.php?id=<?= htmlspecialchars($concessionId) ?>';
            }, 2000);
        });
        
        // Add custom validation styling
        const style = document.createElement('style');
        style.textContent = `
            .form-control.is-invalid {
                border-color: #dc3545;
                box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
