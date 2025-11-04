<?php
// List all zone files
function getZoneFiles() {
    $files = glob(__DIR__ . '/zone/*.json');
    $zones = [];
    foreach($files as $file) {
        $data = json_decode(file_get_contents($file), true);
        if ($data && isset($data['features'][0]['properties'])) {
            $zones[] = [
                'filename' => basename($file),
                'properties' => $data['features'][0]['properties'],
                'coordinates' => $data['features'][0]['geometry']['coordinates'][0]
            ];
        }
    }
    return $zones;
}

$zones = getZoneFiles();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Zones ZAA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .coordinates-section { 
            background: #f8f9fa; 
            padding: 15px; 
            border-radius: 8px; 
            margin-bottom: 15px; 
        }
        .point-row {
            background: white;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }
        .point-row:hover {
            border-color: #4a8eff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand">
                <i class="fas fa-map-marked-alt me-2"></i>
                Gestion des Zones ZAA
            </span>
            <a href="index.html" class="btn btn-outline-light">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </nav>

    <div class="container my-4">
        <div class="row">
            <!-- Zone Form -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-plus-circle text-primary me-2"></i>
                            Nouvelle Zone
                        </h5>
                        
                        <form id="zoneForm" action="save_zone.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nom de la zone (ZAA)</label>
                                <input type="text" class="form-control" name="nom_zaa" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Wilaya</label>
                                <input type="text" class="form-control" name="wilaya" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Commune</label>
                                <input type="text" class="form-control" name="commune" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Superficie (ha)</label>
                                <input type="number" step="0.01" class="form-control" name="superficie_ha" required>
                            </div>
                            
                            <div class="coordinates-section">
                                <h6 class="mb-3">Points de la zone</h6>
                                <div id="coordinatePoints">
                                    <div class="point-row">
                                        <div class="row">
                                            <div class="col">
                                                <label class="form-label">Longitude</label>
                                                <input type="number" step="0.000001" class="form-control" 
                                                       name="lng[]" required>
                                            </div>
                                            <div class="col">
                                                <label class="form-label">Latitude</label>
                                                <input type="number" step="0.000001" class="form-control" 
                                                       name="lat[]" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm mt-3" onclick="addCoordinatePoint()">
                                    <i class="fas fa-plus"></i> Ajouter un point
                                </button>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Enregistrer la zone
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Zones List -->
            <div class="col-lg-6 mt-4 mt-lg-0">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-list text-primary me-2"></i>
                            Zones existantes
                        </h5>
                        
                        <div class="list-group">
                            <?php foreach($zones as $zone): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <h6 class="mb-1"><?= htmlspecialchars($zone['properties']['nom_zaa']) ?></h6>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteZone('<?= $zone['filename'] ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <p class="mb-1">
                                    <?= htmlspecialchars($zone['properties']['wilaya']) ?> - 
                                    <?= htmlspecialchars($zone['properties']['commune']) ?>
                                </p>
                                <small class="text-muted">
                                    <?= $zone['properties']['superficie_ha'] ?> ha
                                </small>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addCoordinatePoint() {
            const container = document.getElementById('coordinatePoints');
            const pointRow = document.createElement('div');
            pointRow.className = 'point-row';
            pointRow.innerHTML = `
                <div class="row">
                    <div class="col">
                        <label class="form-label">Longitude</label>
                        <input type="number" step="0.000001" class="form-control" name="lng[]" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Latitude</label>
                        <input type="number" step="0.000001" class="form-control" name="lat[]" required>
                    </div>
                    <div class="col-auto d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger btn-sm mb-3" 
                                onclick="this.closest('.point-row').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(pointRow);
        }

        function deleteZone(filename) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette zone ?')) {
                fetch('delete_zone.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({filename})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression');
                    }
                });
            }
        }
    </script>
</body>
</html>
