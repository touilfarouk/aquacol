<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="details.title">Détails de la Concession</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
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
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="client-map.html">
                <i class="fas fa-fish me-2"></i>
                <span data-i18n="nav.brand">Gestion des Concessions Aquaculture</span>
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="client-map.html" class="btn btn-outline-light btn-sm me-3">
                    <i class="fas fa-arrow-left me-2"></i><span data-i18n="details.actions.back_to_map">Retour à la carte</span>
                </a>
                <button type="button" id="lang-fr" class="btn btn-sm btn-outline-light" onclick="setLang('fr')">Français</button>
                <button type="button" id="lang-ar" class="btn btn-sm btn-outline-light" onclick="setLang('ar')">العربية</button>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php
        include('config.php');
        
        $concessionParam = $_GET['id'] ?? null;
        
        if (!$concessionParam) {
            echo '<div class="alert alert-danger">ID de concession manquant</div>';
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
            echo '<div class="alert alert-danger">Format d\'ID invalide</div>';
            exit;
        }
        
        try {
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
                    echo '<div class="alert alert-danger">Concession non trouvée ou code invalide</div>';
                } else {
                    echo '<div class="alert alert-danger">Concession non trouvée</div>';
                }
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
                        <h5><i class="fas fa-info-circle me-2"></i><span style="color:green" data-i18n="details.header.info">Informations Générales</span></h5>
                        
                        <!-- <div class="info-item">
                            <div class="info-icon"><i class="fas fa-hashtag"></i></div>
                            <div>
                                <strong data-i18n="details.labels.id">ID:</strong>
                            </div>
                        </div> -->
                        
                        <div class="info-item">
                            <div class="info-icon"><i style="color:lightgreen" class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <strong data-i18n="details.labels.location">Localisation:</strong><br>
                                <?= htmlspecialchars($concession['wilaya_name_ascii'] ?: 'N/A') ?> - 
                                <?= htmlspecialchars($concession['commune_name_ascii'] ?: 'N/A') ?>
                            </div>
                        </div>
                        
                        <?php if ($concession['superficie']): ?>
                        <div class="info-item">
                            <div class="info-icon"><i style="color:orange" class="fas fa-expand-arrows-alt"></i></div>
                            <div>
                                <strong data-i18n="details.labels.area">Superficie:</strong> <?= number_format($concession['superficie'], 2) ?> m²
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($concession['distance_voi_acces']): ?>
                        <div class="info-item">
                            <div class="info-icon"><i style="color:pink" class="fas fa-road"></i></div>
                            <div>
                                <strong data-i18n="details.labels.road_distance">Distance voie d'accès:</strong> <?= $concession['distance_voi_acces'] ?>m
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="info-item">
                            <div class="info-icon"><i style="color:lightblue" class="fas fa-eye"></i></div>
                            <div>
                                <strong data-i18n="details.labels.visibility">Visibilité:</strong> 
                                <?php if ($concession['visible']): ?>
                                    <span data-i18n="details.visibility.visible">Visible</span>
                                <?php else: ?>
                                    <span data-i18n="details.visibility.hidden">Masqué</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($concession['description']): ?>
                        <div class="info-item">
                            <div class="info-icon"><i style="color:brown" class="fas fa-file-text"></i></div>
                            <div>
                                <strong data-i18n="details.labels.description">Description:</strong><br>
                                <?= nl2br(htmlspecialchars($concession['description'])) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6">
                        <h5><i class="fas fa-map me-2"></i><span data-i18n="details.map.title">Localisation sur la Carte</span></h5>
                        <div id="map"></div>
                        
                        <div class="mt-3">
                            <h6><i class="fas fa-crosshairs me-2"></i><span data-i18n="details.coordinates.title">Coordonnées géographiques</span></h6>
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
                        <a href="application.php?id=<?= htmlspecialchars($concession['code_concession'] ? $concession['code_concession'] . '-' . $concession['id'] : $concession['id']) ?>" class="btn btn-warning btn-lg me-3">
                            <i class="fas fa-file-alt me-2"></i><span  data-i18n="details.actions.apply">Je dépose ma demande</span>
                        </a>
                        <!-- <button onclick="generatePDF()" class="btn btn-success btn-lg me-3">
                            <i class="fas fa-file-pdf me-2"></i><span data-i18n="details.actions.generate_pdf">Générer Fiche Technique PDF</span>
                        </button> -->
                        <a href="client-map.html" class="btn btn-outline-success btn-lg">
                            <i class="fas fa-arrow-left me-2"></i><span data-i18n="details.actions.back_to_map">Retour à la carte</span>
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
    <!-- i18n loader (reads ?lang=fr|ar and applies langs/*.json) -->
    <script src="langs/i18n.js"></script>
    
    <script>
        // Language switcher that preserves current id & other params
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
        // Initialize map
        const concessionData = <?= json_encode($concession ?? null) ?>;

(function () {
  // Helper to parse "lat lng" or "lat,lng"
  function parseCoordinateString(coordStr) {
    if (!coordStr) return [NaN, NaN];
    const parts = coordStr.toString().trim().split(/[\s,]+/);
    return [parseFloat(parts[0]), parseFloat(parts[1])];
  }

  // Nothing to do if no concession data
  if (!concessionData || !concessionData.coordonnee_a) return;

  // Parse coords
  const coords = [
    parseCoordinateString(concessionData.coordonnee_a),
    parseCoordinateString(concessionData.coordonnee_b),
    parseCoordinateString(concessionData.coordonnee_c),
    parseCoordinateString(concessionData.coordonnee_d)
  ];

  // Validate
  const validCoords = coords.every(c => Array.isArray(c) && !isNaN(c[0]) && !isNaN(c[1]));
  if (!validCoords) {
    console.error('Invalid concession coordinates', coords);
    return;
  }

  // Compute center
  const centerLat = coords.reduce((sum, c) => sum + c[0], 0) / coords.length;
  const centerLng = coords.reduce((sum, c) => sum + c[1], 0) / coords.length;

  // If a Leaflet map already exists on this page (same container), remove it first
  if (window._singleMap && window._singleMap instanceof L.Map) {
    try {
      window._singleMap.off();
      window._singleMap.remove();
    } catch (e) {
      // ignore
      console.warn('Previous map removal error', e);
    }
  }

  // Create map and save it globally so subsequent code can remove it if needed
  const map = L.map('map', { zoomControl: true }).setView([centerLat, centerLng], 16);
  window._singleMap = map;

  // Define base layers (Google + OSM fallback)
  const terrainLayer = L.tileLayer('https://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}', {
    attribution: '&copy; <a href="https://www.google.com/maps">Google Maps</a>',
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    maxZoom: 20
  });

  const satelliteLayer = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
    attribution: '&copy; <a href="https://www.google.com/maps">Google Maps</a>',
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    maxZoom: 20
  });

  const osmFallback = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19,
    subdomains: ['a','b','c']
  });

  // Add default (Terrain)
  terrainLayer.addTo(map);

  // Simple tileerror fallback: if several tiles fail, switch to OSM
  let tileErrorCount = 0;
  const MAX_TILE_ERRORS = 6;
  function onTileError() {
    tileErrorCount++;
    if (tileErrorCount >= MAX_TILE_ERRORS) {
      // remove google layers and add OSM fallback
      try {
        if (map.hasLayer(terrainLayer)) map.removeLayer(terrainLayer);
        if (map.hasLayer(satelliteLayer)) map.removeLayer(satelliteLayer);
      } catch(_) {}
      osmFallback.addTo(map);
      // Remove listeners to avoid repeated switching
      terrainLayer.off('tileerror', onTileError);
      satelliteLayer.off('tileerror', onTileError);
      console.warn('Switched to OSM fallback due to repeated tile errors.');
    }
  }
  terrainLayer.on('tileerror', onTileError);
  satelliteLayer.on('tileerror', onTileError);

  // Add a layers control so user can switch
  L.control.layers(
    { 'Terrain': terrainLayer, 'Satellite': satelliteLayer, 'OSM (fallback)': osmFallback },
    {}, // overlays (none)
    { collapsed: false }
  ).addTo(map);

  // Create custom icon (keeps your settings)
  const customIcon = L.icon({
    iconUrl: 'icons.png',
    iconSize: [40, 60],
    iconAnchor: [20, 60],
    popupAnchor: [0, -60]
  });

  // Add marker at center
  L.marker([centerLat, centerLng], { icon: customIcon }).addTo(map);

  // Add polygon (square)
  L.polygon(coords, {
    color: '#28a745',
    weight: 2,
    fillOpacity: 0.2
  }).addTo(map);

  // Optional: fit bounds to the polygon for better framing (comment out if you prefer fixed zoom)
  try {
    const bounds = L.latLngBounds(coords);
    map.fitBounds(bounds.pad(0.15));
  } catch (e) {
    // ignore
  }

})();
</script>