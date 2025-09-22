<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Concession</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .detail-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .detail-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
        }
        
        #map {
            height: 400px;
            border-radius: 8px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .info-icon {
            width: 40px;
            text-align: center;
            margin-right: 15px;
            color: var(--primary-color);
        }
        
        .code-badge {
            background: var(--primary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-family: monospace;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <i class="fas fa-fish me-2"></i>
                Gestion des Concessions Aquaculture
            </a>
            <a href="index.html" class="btn btn-outline-light btn-sm">
                <i class="fas fa-arrow-left me-2"></i>Retour à la carte
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <?php
        include('config.php');
        
        $concessionId = $_GET['id'] ?? null;
        
        if (!$concessionId) {
            echo '<div class="alert alert-danger">ID de concession manquant</div>';
            exit;
        }
        
        try {
            $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            
            $stmt = $pdo->prepare("SELECT * FROM coordinates WHERE id = :id");
            $stmt->execute([':id' => $concessionId]);
            $concession = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$concession) {
                echo '<div class="alert alert-danger">Concession non trouvée</div>';
                exit;
            }
        ?>
        
        <div class="detail-card">
            <div class="detail-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h2><i class="fas fa-fish me-3"></i><?= htmlspecialchars($concession['nom_zone'] ?: 'Concession') ?></h2>
                        <p class="mb-0">Créée le <?= date('d/m/Y à H:i', strtotime($concession['created_at'])) ?></p>
                    </div>
                    <div class="col-auto">
                        <span class="code-badge"><?= htmlspecialchars($concession['code_concession'] ?: 'N/A') ?></span>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-info-circle me-2"></i>Informations Générales</h5>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-hashtag"></i></div>
                            <div>
                                <strong>ID:</strong> <?= htmlspecialchars($concession['id']) ?>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <strong>Localisation:</strong><br>
                                <?= htmlspecialchars($concession['wilaya_name_ascii'] ?: 'N/A') ?> - 
                                <?= htmlspecialchars($concession['commune_name_ascii'] ?: 'N/A') ?>
                            </div>
                        </div>
                        
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
                                <strong>Distance voie d'accès:</strong> <?= $concession['distance_voi_acces'] ?>m
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-eye"></i></div>
                            <div>
                                <strong>Visibilité:</strong> 
                                <?= $concession['visible'] ? 'Visible' : 'Masqué' ?>
                            </div>
                        </div>
                        
                        <?php if ($concession['description']): ?>
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-file-text"></i></div>
                            <div>
                                <strong>Description:</strong><br>
                                <?= nl2br(htmlspecialchars($concession['description'])) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6">
                        <h5><i class="fas fa-map me-2"></i>Localisation sur la Carte</h5>
                        <div id="map"></div>
                        
                        <div class="mt-3">
                            <h6><i class="fas fa-crosshairs me-2"></i>Coordonnées (50m x 50m)</h6>
                            <div style="font-family: 'Courier New', monospace; font-size: 0.9rem; background: #f8f9fa; padding: 15px; border-radius: 8px;">
                                <div><strong>A (NO):</strong> <?= htmlspecialchars($concession['coordonnee_a']) ?></div>
                                <div><strong>B (NE):</strong> <?= htmlspecialchars($concession['coordonnee_b']) ?></div>
                                <div><strong>C (SE):</strong> <?= htmlspecialchars($concession['coordonnee_c']) ?></div>
                                <div><strong>D (SO):</strong> <?= htmlspecialchars($concession['coordonnee_d']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col text-center">
                        <button onclick="generatePDF()" class="btn btn-success btn-lg me-3">
                            <i class="fas fa-file-pdf me-2"></i>Générer Fiche Technique PDF
                        </button>
                        <a href="index.html" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la carte
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Initialize map
        const concessionData = <?= json_encode($concession ?? []) ?>;
        
        if (concessionData.coordonnee_a) {
            // Parse coordinates
            const coords = [
                parseCoordinateString(concessionData.coordonnee_a),
                parseCoordinateString(concessionData.coordonnee_b),
                parseCoordinateString(concessionData.coordonnee_c),
                parseCoordinateString(concessionData.coordonnee_d)
            ];
            
            // Calculate center
            const centerLat = coords.reduce((sum, coord) => sum + coord[0], 0) / 4;
            const centerLng = coords.reduce((sum, coord) => sum + coord[1], 0) / 4;
            
            // Create map
            const map = L.map('map').setView([centerLat, centerLng], 16);
            
            // Add tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Create custom icon
            const customIcon = L.icon({
                iconUrl: 'icon.png',
                iconSize: [40, 60],
                iconAnchor: [20, 60],
                popupAnchor: [0, -60]
            });
            
            // Add marker
            L.marker([centerLat, centerLng], {
                icon: customIcon
            }).addTo(map);
            
            // Add square
            L.polygon(coords, {
                color: '#28a745',
                weight: 2,
                fillOpacity: 0.2
            }).addTo(map);
        }
        
        function parseCoordinateString(coordStr) {
            const parts = coordStr.split(/[\s,]+/);
            return [parseFloat(parts[0]), parseFloat(parts[1])];
        }
        
        function generatePDF() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'generate_pdf.php';
            form.target = '_blank';
            
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'concession_id';
            idInput.value = concessionData.id;
            form.appendChild(idInput);
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    </script>
</body>
</html>
