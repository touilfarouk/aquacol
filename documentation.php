<?php
// Configuration
$APP_VERSION = "1.0.0";
$LAST_UPDATED = date("Y-m-d");

// Get current feature list from the codebase
function getFeatureList() {
    return [
        "Carte" => [
            "Visualisation des zones aquacoles",
            "Sélection de couches (Satellite/Carte)",
            "Gestion des vertices avec coordonnées",
            "Création/édition de zones"
        ],
        "Édition" => [
            "Modification des vertices par drag & drop",
            "Affichage des coordonnées en temps réel",
            "Sauvegarde/réinitialisation des modifications",
            "Export GeoJSON"
        ],
        "Gestion" => [
            "Sélection de zones par wilaya",
            "Attribution de concessions",
            "Génération de documents PDF",
            "Gestion multilingue (FR/AR)"
        ]
    ];
}

// Get screenshots from /screenshots directory
function getScreenshots() {
    $screenshots = glob("screenshots/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    return array_map(function($path) {
        return [
            'path' => $path,
            'name' => basename($path),
            'date' => date("Y-m-d", filemtime($path))
        ];
    }, $screenshots);
}

$features = getFeatureList();
$screenshots = getScreenshots();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation - Système de Gestion des Concessions Aquaculture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --brand: #4a8eff; }
        body { background: #f8fafc; }
        .doc-header { 
            background: linear-gradient(135deg,var(--brand),#6b9cff);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .doc-section {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }
        .screenshot {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            margin: 1rem 0;
            max-width: 100%;
        }
        .feature-list li { margin-bottom: 0.5rem; }
        .toc a {
            display: block;
            padding: 0.25rem 0;
            color: var(--brand);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <header class="doc-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>Documentation Technique</h1>
                    <p class="mb-0">Version <?= $APP_VERSION ?> - Mise à jour: <?= $LAST_UPDATED ?></p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="index.html" class="btn btn-light">
                        <i class="fas fa-arrow-left"></i> Retour à l'application
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container pb-5">
        <div class="row">
            <div class="col-md-3">
                <div class="doc-section">
                    <h5>Table des matières</h5>
                    <nav class="toc">
                        <a href="#intro">Introduction</a>
                        <a href="#features">Fonctionnalités</a>
                        <a href="#screenshots">Captures d'écran</a>
                        <a href="#api">API Reference</a>
                        <a href="#tech">Notes techniques</a>
                    </nav>
                </div>
            </div>

            <div class="col-md-9">
                <section id="intro" class="doc-section">
                    <h3>Introduction</h3>
                    <p>Le Système de Gestion des Concessions Aquaculture permet la gestion et la visualisation des zones de concessions aquacoles. Cette documentation présente les fonctionnalités disponibles et leur utilisation.</p>
                </section>

                <section id="features" class="doc-section">
                    <h3>Fonctionnalités</h3>
                    <?php foreach($features as $category => $items): ?>
                    <div class="mb-4">
                        <h5><?= $category ?></h5>
                        <ul class="feature-list">
                            <?php foreach($items as $item): ?>
                            <li><?= $item ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endforeach; ?>
                </section>

                <section id="screenshots" class="doc-section">
                    <h3>Captures d'écran</h3>
                    <?php if(empty($screenshots)): ?>
                        <div class="alert alert-info">
                            Placez vos captures d'écran dans le dossier /screenshots
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach($screenshots as $screenshot): ?>
                            <div class="col-md-6 mb-4">
                                <img src="<?= $screenshot['path'] ?>" 
                                     alt="<?= $screenshot['name'] ?>" 
                                     class="screenshot">
                                <div class="small text-muted">
                                    <?= $screenshot['name'] ?> (<?= $screenshot['date'] ?>)
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>

                <section id="api" class="doc-section">
                    <h3>API Reference</h3>
                    <ul>
                        <li><code>GET /api.php</code> - Liste des concessions</li>
                        <li><code>POST /api.php</code> - Créer/modifier une concession</li>
                        <li><code>GET /get_coordinates.php</code> - Coordonnées des concessions</li>
                        <li><code>POST /save_zone.php</code> - Sauvegarder une zone</li>
                    </ul>
                </section>

                <section id="tech" class="doc-section">
                    <h3>Notes techniques</h3>
                    <ul>
                        <li>Système de coordonnées: WGS84 (EPSG:4326)</li>
                        <li>Formats supportés: DD, DMS, UTM</li>
                        <li>Export: GeoJSON, PDF (via Pandoc)</li>
                        <li>Langues: Français, Arabe</li>
                    </ul>
                </section>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
