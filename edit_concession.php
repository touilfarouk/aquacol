<?php
require_once 'config.php';

// Check if ID is provided and valid
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: admin_concessions.php');
    exit();
}

$success_message = '';
$error_message   = '';
$concession      = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        // Collect form data
        $codeConcession    = trim($_POST['code_concession'] ?? '');
        $nomZone           = trim($_POST['nom_zone'] ?? '');
        $coordonneeA       = trim($_POST['coordonnee_a'] ?? '');
        $coordonneeB       = trim($_POST['coordonnee_b'] ?? '');
        $coordonneeC       = trim($_POST['coordonnee_c'] ?? '');
        $coordonneeD       = trim($_POST['coordonnee_d'] ?? '');
        $wilayaName        = trim($_POST['wilaya_name_ascii'] ?? '');
        $communeName       = trim($_POST['commune_name_ascii'] ?? '');
        $codeWilaya        = trim($_POST['code_wilaya'] ?? '');
        $codeCommune       = trim($_POST['code_commune'] ?? '');
        $superficie        = trim($_POST['superficie'] ?? 0);
        $visible           = isset($_POST['visible']) ? 1 : 0;
        $distanceVoiAcces  = trim($_POST['distance_voi_acces'] ?? 0);
        $statut            = trim($_POST['statut'] ?? 'inactive');
        $description       = trim($_POST['description'] ?? '');

        // Validation
        if (empty($nomZone) || empty($codeConcession) || empty($coordonneeA) || empty($coordonneeB) || empty($coordonneeC) || empty($coordonneeD)) {
            throw new Exception("Tous les champs obligatoires doivent être remplis.");
        }

        // Update query with all fields
        $sql = "UPDATE coordinates 
                   SET code_concession=:code_concession,
                       nom_zone=:nom_zone,
                       wilaya_name_ascii=:wilaya_name_ascii,
                       commune_name_ascii=:commune_name_ascii,
                       code_wilaya=:code_wilaya,
                       code_commune=:code_commune,
                       superficie=:superficie,
                       visible=:visible,
                       distance_voi_acces=:distance_voi_acces,
                       coordonnee_a=:a,
                       coordonnee_b=:b,
                       coordonnee_c=:c,
                       coordonnee_d=:d,
                       statut=:statut,
                       description=:description
                 WHERE id=:id";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':code_concession'    => $codeConcession,
            ':nom_zone'           => $nomZone,
            ':wilaya_name_ascii'  => $wilayaName,
            ':commune_name_ascii' => $communeName,
            ':code_wilaya'        => $codeWilaya,
            ':code_commune'       => $codeCommune,
            ':superficie'         => $superficie,
            ':visible'            => $visible,
            ':distance_voi_acces' => $distanceVoiAcces,
            ':a'                  => $coordonneeA,
            ':b'                  => $coordonneeB,
            ':c'                  => $coordonneeC,
            ':d'                  => $coordonneeD,
            ':statut'             => $statut,
            ':description'        => $description,
            ':id'                 => $id,
        ]);

        if ($result) {
            $success_message = "Concession mise à jour avec succès.";
        } else {
            throw new Exception("Erreur lors de la mise à jour.");
        }

    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Load concession data
try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $stmt = $pdo->prepare("SELECT * FROM coordinates WHERE id=:id");
    $stmt->execute([':id' => $id]);
    $concession = $stmt->fetch();
} catch (Exception $e) {
    $error_message = "Erreur lors du chargement de la concession: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Concession - Administration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .form-container { max-width: 800px; margin: 0 auto; }
        .map-container { height: 300px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 4px; }
        #map { height: 100%; width: 100%; }
        
        /* Custom success alert */
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-success .btn-close {
            color: #155724;
        }
        .coord-input {
            background-color: #f8f9fa !important;
            cursor: not-allowed;
        }
        .coord-input:focus {
            background-color: #f8f9fa !important;
            border-color: #ced4da;
            box-shadow: none;
        }
    </style>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <script>
        // Auto-hide notifications after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Administration</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_concessions.php">Concessions</a>
                    </li>
                </ul>
                <!-- <a href="index.php" class="btn btn-light">Retour au site</a> -->
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Modifier la concession</h2>
            <a href="admin_concessions.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                <i class="fas fa-check-circle me-2"></i>
                <?= htmlspecialchars($success_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= htmlspecialchars($error_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        

        <div class="card form-container">
            <div class="card-body">
                <form method="POST" id="concessionForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code_concession" class="form-label">Code de la concession</label>
                            <input type="text" class="form-control" id="code_concession" name="code_concession" 
                                   value="<?= htmlspecialchars($concession['code_concession'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nom_zone" class="form-label">Nom de la zone</label>
                            <input type="text" class="form-control" id="nom_zone" name="nom_zone" 
                                   value="<?= htmlspecialchars($concession['nom_zone'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="wilaya_name_ascii" class="form-label">Wilaya</label>
                            <input type="text" class="form-control" id="wilaya_name_ascii" name="wilaya_name_ascii" 
                                   value="<?= htmlspecialchars($concession['wilaya_name_ascii'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="commune_name_ascii" class="form-label">Commune</label>
                            <input type="text" class="form-control" id="commune_name_ascii" name="commune_name_ascii" 
                                   value="<?= htmlspecialchars($concession['commune_name_ascii'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="code_wilaya" class="form-label">Code Wilaya</label>
                            <input type="text" class="form-control" id="code_wilaya" name="code_wilaya" 
                                   value="<?= htmlspecialchars($concession['code_wilaya'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="code_commune" class="form-label">Code Commune</label>
                            <input type="text" class="form-control" id="code_commune" name="code_commune" 
                                   value="<?= htmlspecialchars($concession['code_commune'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="superficie" class="form-label">Superficie (m²)</label>
                            <input type="number" step="0.01" class="form-control" id="superficie" name="superficie" 
                                   value="<?= htmlspecialchars($concession['superficie'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="visible" name="visible" value="1" 
                                    <?= isset($concession['visible']) && $concession['visible'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="visible">Visible</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="distance_voi_acces" class="form-label">Distance voirie d'accès (m)</label>
                            <input type="number" class="form-control" id="distance_voi_acces" name="distance_voi_acces" 
                                   value="<?= htmlspecialchars($concession['distance_voi_acces'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="coordonnee_a" class="form-label">Coordonnée A</label>
                            <input type="text" class="form-control coord-input" id="coordonnee_a" name="coordonnee_a" 
                                   value="<?= htmlspecialchars($concession['coordonnee_a'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="coordonnee_b" class="form-label">Coordonnée B</label>
                            <input type="text" class="form-control coord-input" id="coordonnee_b" name="coordonnee_b" 
                                   value="<?= htmlspecialchars($concession['coordonnee_b'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="coordonnee_c" class="form-label">Coordonnée C</label>
                            <input type="text" class="form-control coord-input" id="coordonnee_c" name="coordonnee_c" 
                                   value="<?= htmlspecialchars($concession['coordonnee_c'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="coordonnee_d" class="form-label">Coordonnée D</label>
                            <input type="text" class="form-control coord-input" id="coordonnee_d" name="coordonnee_d" 
                                   value="<?= htmlspecialchars($concession['coordonnee_d'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="statut" class="form-label">Statut</label>
                            <select class="form-select" id="statut" name="statut">
                                <option value="active" <?= isset($concession['statut']) && $concession['statut'] === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= isset($concession['statut']) && $concession['statut'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                <option value="en_attente" <?= isset($concession['statut']) && $concession['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($concession['description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Localisation sur la carte</label>
                        <div class="map-container">
                            <div id="map"></div>
                        </div>
                        <div class="form-text">Cliquez sur la carte pour positionner la concession (carré de 50x50 mètres)</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="format_coordonnees" class="form-label">Format coordonnées</label>
                            <select class="form-select" id="format_coordonnees" name="format_coordonnees" disabled>
                                <option value="decimal" selected>Décimal</option>
                            </select>
                            <small class="text-muted">Cliquez sur la carte pour définir l'emplacement</small>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="admin_concessions.php" class="btn btn-secondary me-md-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the map
        const map = L.map('map').setView([36.7525, 3.042], 10);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        let marker = null;
        
        // Function to update coordinate fields
        function updateCoordinateFields(lat, lng) {
            // Convert lat/lng to decimal degrees with 6 decimal places
            const latStr = lat.toFixed(6);
            const lngStr = lng.toFixed(6);
            
            // Calculate the 50x50 meter square coordinates (approximately 0.00045 degrees = ~50m at equator)
            const offset = 0.00045;
            
            // Update the coordinate fields
            document.getElementById('coordonnee_a').value = `${latStr}, ${lngStr}`;
            document.getElementById('coordonnee_b').value = `${(lat + offset).toFixed(6)}, ${lngStr}`;
            document.getElementById('coordonnee_c').value = `${(lat + offset).toFixed(6)}, ${(lng + offset).toFixed(6)}`;
            document.getElementById('coordonnee_d').value = `${latStr}, ${(lng + offset).toFixed(6)}`;
            
            // Trigger change event on inputs to update any listeners
            document.querySelectorAll('.coord-input').forEach(input => input.dispatchEvent(new Event('change')));
        }
        
        // Handle map click
        map.on('click', function(e) {
            const { lat, lng } = e.latlng;
            
            // Remove existing marker if any
            if (marker) {
                map.removeLayer(marker);
            }
            
            // Add new marker
            marker = L.marker([lat, lng], {
                draggable: true,
                title: 'Emplacement de la concession'
            }).addTo(map);
            
            // Update coordinates when marker is dragged
            marker.on('dragend', function(e) {
                const newLatLng = e.target.getLatLng();
                updateCoordinateFields(newLatLng.lat, newLatLng.lng);
            });
            
            // Update coordinate fields
            updateCoordinateFields(lat, lng);
            
            // Show popup with coordinates
            marker.bindPopup(`
                <div style="text-align: center;">
                    <strong>Coordonnées de la concession</strong><br>
                    A: ${lat.toFixed(6)}, ${lng.toFixed(6)}<br>
                    B: ${(lat + 0.00045).toFixed(6)}, ${lng.toFixed(6)}<br>
                    C: ${(lat + 0.00045).toFixed(6)}, ${(lng + 0.00045).toFixed(6)}<br>
                    D: ${lat.toFixed(6)}, ${(lng + 0.00045).toFixed(6)}
                </div>
            `).openPopup();
        });
        
        // If there are existing coordinates, add a marker
        const coordA = document.getElementById('coordonnee_a').value;
        if (coordA) {
            try {
                const [lat, lng] = coordA.split(',').map(Number);
                if (!isNaN(lat) && !isNaN(lng)) {
                    marker = L.marker([lat, lng], {
                        draggable: true,
                        title: 'Emplacement de la concession'
                    }).addTo(map);
                    
                    // Center the map on the marker
                    map.setView([lat, lng], 15);
                    
                    // Add popup
                    marker.bindPopup(`
                        <div style="text-align: center;">
                            <strong>Coordonnées de la concession</strong><br>
                            A: ${lat.toFixed(6)}, ${lng.toFixed(6)}<br>
                            B: ${(lat + 0.00045).toFixed(6)}, ${lng.toFixed(6)}<br>
                            C: ${(lat + 0.00045).toFixed(6)}, ${(lng + 0.00045).toFixed(6)}<br>
                            D: ${lat.toFixed(6)}, ${(lng + 0.00045).toFixed(6)}
                        </div>
                    `);
                }
            } catch (e) {
                console.error('Error parsing coordinates:', e);
            }
        }
    });
    </script>
</body>
</html>
