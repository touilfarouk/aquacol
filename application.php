<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application - Concession Aquaculture</title>
    
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
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html">
                <i class="fas fa-fish me-2"></i>
                Gestion des Concessions Aquaculture
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.html">
                    <i class="fas fa-map me-1"></i>Carte
                </a>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <?php
        // Get concession ID from URL
        $concessionId = $_GET['id'] ?? null;
        
        if (!$concessionId) {
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>ID de concession manquant</div>';
            exit;
        }
        
        try {
            // Database connection
            require_once('config.php');
            $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            
            // Get concession data
            $stmt = $pdo->prepare("SELECT * FROM coordinates WHERE id = :id");
            $stmt->execute([':id' => $concessionId]);
            $concession = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$concession) {
                echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Concession non trouvée</div>';
                exit;
            }
        ?>
        
        <div class="application-card">
            <div class="card-header">
                <h1><i class="fas fa-file-alt me-3"></i>Demande d'Application</h1>
                <p class="mb-0">Concession: <?= htmlspecialchars($concession['nom_zone'] ?? 'Zone ' . $concession['id']) ?></p>
            </div>
            
            <div class="card-body">
                <!-- Concession Information -->
                <div class="info-section">
                    <h4><i class="fas fa-info-circle me-2"></i>Informations de la Concession</h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-hashtag"></i></div>
                                <div>
                                    <strong>ID:</strong> <?= htmlspecialchars($concession['id']) ?>
                                </div>
                            </div>
                            
                            <?php if ($concession['code_concession']): ?>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-barcode"></i></div>
                                <div>
                                    <strong>Code Concession:</strong> <?= htmlspecialchars($concession['code_concession']) ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div>
                                    <strong>Localisation:</strong><br>
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
                                    <strong>Superficie:</strong> <?= number_format($concession['superficie'], 2) ?> m²
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($concession['distance_voi_acces']): ?>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-road"></i></div>
                                <div>
                                    <strong>Distance voie d'accès:</strong> <?= htmlspecialchars($concession['distance_voi_acces']) ?> mètres
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-calendar"></i></div>
                                <div>
                                    <strong>Date de création:</strong> <?= date('d/m/Y H:i', strtotime($concession['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Application Form -->
                <div class="application-form">
                    <h4><i class="fas fa-file-signature me-2"></i>Formulaire de Demande</h4>
                    
                    <form id="applicationForm" method="POST" action="process_application.php">
                        <input type="hidden" name="concession_id" value="<?= htmlspecialchars($concessionId) ?>">
                        
                        <div class="form-section">
                            <h5><i class="fas fa-user me-2"></i>Informations du Demandeur</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="applicant_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="applicant_email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="applicant_phone" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Numéro CIN/Passeport <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="applicant_id_number" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Adresse complète <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="applicant_address" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h5><i class="fas fa-building me-2"></i>Informations de l'Entreprise/Organisation</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nom de l'entreprise</label>
                                        <input type="text" class="form-control" name="company_name">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Numéro de registre commercial</label>
                                        <input type="text" class="form-control" name="company_registration">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Secteur d'activité</label>
                                        <select class="form-select" name="activity_sector">
                                            <option value="">Sélectionner un secteur</option>
                                            <option value="pisciculture">Pisciculture</option>
                                            <option value="conchyliculture">Conchyliculture</option>
                                            <option value="algaculture">Algaculture</option>
                                            <option value="crustaculture">Crustaculture</option>
                                            <option value="autre">Autre</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Expérience dans le domaine (années)</label>
                                        <input type="number" class="form-control" name="experience_years" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h5><i class="fas fa-fish me-2"></i>Détails du Projet Aquacole</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Type d'aquaculture <span class="text-danger">*</span></label>
                                        <select class="form-select" name="aquaculture_type" required>
                                            <option value="">Sélectionner le type</option>
                                            <option value="marine">Aquaculture marine</option>
                                            <option value="eau_douce">Aquaculture d'eau douce</option>
                                            <option value="saumatre">Aquaculture d'eau saumâtre</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Espèces à élever <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="species_to_raise" required placeholder="Ex: Dorade, Bar, Moules...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Production annuelle prévue (tonnes)</label>
                                        <input type="number" class="form-control" name="annual_production" min="0" step="0.1">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Investissement prévu (DZD)</label>
                                        <input type="number" class="form-control" name="planned_investment" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description détaillée du projet <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="project_description" rows="4" required placeholder="Décrivez votre projet aquacole en détail..."></textarea>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h5><i class="fas fa-file-upload me-2"></i>Documents Requis</h5>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Documents à fournir:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Copie de la pièce d'identité</li>
                                    <li>Extrait de casier judiciaire</li>
                                    <li>Certificat de résidence</li>
                                    <li>Étude technique du projet</li>
                                    <li>Étude d'impact environnemental</li>
                                    <li>Justificatifs financiers</li>
                                </ul>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Commentaires sur les documents</label>
                                <textarea class="form-control" name="documents_comments" rows="3" placeholder="Précisez l'état de vos documents ou toute information pertinente..."></textarea>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="terms_accepted" id="termsCheck" required>
                                <label class="form-check-label" for="termsCheck">
                                    <strong>J'accepte les termes et conditions</strong> et je certifie que toutes les informations fournies sont exactes.
                                </label>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg me-3">
                                <i class="fas fa-paper-plane me-2"></i>Soumettre la Demande
                            </button>
                            <a href="details.php?id=<?= htmlspecialchars($concessionId) ?>" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux Détails
                            </a>
                        </div>
                    </form>
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
    <script>
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
