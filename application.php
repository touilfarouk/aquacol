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
    
    <style>
        :root {
            --primary-color: #4a8eff;
            --secondary-color: #4a8eff;
            --success-color: #4a8eff;
            --warning-color: #4a8eff;
            --danger-color: #4a8eff;
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
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
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
                <h1><i class="fas fa-file-alt me-3"></i><span data-i18n="application.title">Demande d'Application</span></h1>
                <p class="mb-0"><span data-i18n="application.concession">Concession</span>: <?= htmlspecialchars($concession['nom_zone'] ?? 'Zone ' . $concession['id']) ?></p>
            </div>
            
            <div class="card-body">
                <!-- Concession Information -->
                <div class="info-section">
                    <h4><i class="fas fa-info-circle me-2"></i><span data-i18n="application.concession_info.title">Informations de la Concession</span></h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-hashtag"></i></div>
                                <div>
                                    <strong data-i18n="application.concession_info.id">ID:</strong> <?= htmlspecialchars($concession['id']) ?>
                                </div>
                            </div>
                            
                            <?php if ($concession['code_concession']): ?>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-barcode"></i></div>
                                <div>
                                    <strong data-i18n="application.concession_info.code">Code de concession:</strong> <?= htmlspecialchars($concession['code_concession']) ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div>
                                    <strong data-i18n="application.concession_info.location">Localisation:</strong><br>
                                    <?= htmlspecialchars($concession['wilaya_name_ascii'] ?? 'N/A') ?> - 
                                    <?= htmlspecialchars($concession['commune_name_ascii'] ?? 'N/A') ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <?php if ($concession['superficie']): ?>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-expand-arrows-alt"></i></div>
                                <div>
                                    <strong data-i18n="application.concession_info.area">Superficie:</strong> <?= number_format($concession['superficie'], 2) ?> m²
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($concession['distance_voi_acces']): ?>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-road"></i></div>
                                <div>
                                    <strong data-i18n="application.concession_info.road_distance">Distance voie d'accès:</strong> <?= htmlspecialchars($concession['distance_voi_acces']) ?> m
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-calendar"></i></div>
                                <div>
                                    <strong data-i18n="application.concession_info.created_date">Date de création:</strong> <?= date('d/m/Y H:i', strtotime($concession['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
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
                    <h4><i class="fas fa-file-signature me-2"></i><span data-i18n="application.title">Demande d'Octroi de Concession Aquacole</span></h4>

                    <!-- Progress Steps -->
                    <div class="progress-steps mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-nowrap">
                            <div class="step-container d-flex flex-column align-items-center">
                                <div class="step-icon active">1</div>
                                <span class="text-center small fw-medium" data-i18n="wizard.steps.1">Porteur de projet</span>
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
                            <h5 class="mb-3">1. Porteur de projet</h5>
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
                            <h5 class="mb-3">2. Identification du projet</h5>
                            <div class="bg-light p-3 rounded mb-3">
                                <h6 class="text-primary mb-2">Informations de base</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Dénomination du projet</label>
                                        <input type="text" class="form-control" name="denomination_projet">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Date de la demande</label>
                                        <input type="date" class="form-control" name="date_demande">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-outline-secondary" onclick="goToStep(1)" data-i18n="actions.previous">Précédent</button>
                                <button type="button" class="btn btn-primary" onclick="goToStep(3)" data-i18n="actions.next">Suivant</button>
                            </div>
                        </div>

                        <!-- Step 3: Détails du projet -->
                        <div class="wizard-section" data-step="3">
                            <h5 class="mb-3">3. Détails du projet</h5>
                            <div class="bg-light p-3 rounded mb-3">
                                <h6 class="text-primary mb-2">Espèces d'élevage</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="especes[]" value="daurade">
                                            <label class="form-check-label">Daurade royale</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="especes[]" value="loup">
                                            <label class="form-check-label">Loup de mer (Bar)</label>
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
                            <h5 class="mb-3">4. Spécifications techniques</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Capacité de production (Tonnes/cycle)</label>
                                    <input type="number" class="form-control" name="capacite_production">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Durée de réalisation (mois)</label>
                                    <input type="number" class="form-control" name="duree_realisation">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-outline-secondary" onclick="goToStep(3)" data-i18n="actions.previous">Précédent</button>
                                <button type="button" class="btn btn-primary" onclick="goToStep(5)" data-i18n="actions.next">Suivant</button>
                            </div>
                        </div>

                        <!-- Step 5: Documents -->
                        <div class="wizard-section" data-step="5">
                            <h5 class="mb-3">5. Documents</h5>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Documents à fournir:</strong> Copie d'identité, Casier judiciaire, Étude technique, etc.
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="termsCheck" required>
                                <label class="form-check-label" for="termsCheck">J'accepte les termes et conditions</label>
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
