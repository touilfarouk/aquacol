<?php
require_once('config.php');

// Include the main TCPDF library
require_once('TCPDF-main/tcpdf.php');

// Check if TCPDF is available, if not provide instructions
if (!class_exists('TCPDF')) {
    // Create a simple HTML version for now
    $concessionId = $_POST['concession_id'] ?? $_GET['id'] ?? null;
    
    if (!$concessionId) {
        die('ID de concession manquant');
    }
    
    try {
        $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        $stmt = $pdo->prepare("SELECT * FROM coordinates WHERE id = :id");
        $stmt->execute([':id' => $concessionId]);
        $concession = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$concession) {
            die('Concession non trouvée');
        }
        
        // Set headers for PDF download (will be HTML for now)
        header('Content-Type: text/html; charset=utf-8');
        
        // Parse coordinates for map
        $coords = [
            parseCoordinateString($concession['coordonnee_a']),
            parseCoordinateString($concession['coordonnee_b']),
            parseCoordinateString($concession['coordonnee_c']),
            parseCoordinateString($concession['coordonnee_d'])
        ];
        
        $centerLat = array_sum(array_column($coords, 0)) / 4;
        $centerLng = array_sum(array_column($coords, 1)) / 4;
        
        function parseCoordinateString($coordStr) {
            $parts = preg_split('/[\s,]+/', $coordStr);
            return [floatval($parts[0]), floatval($parts[1])];
        }
        
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Fiche Technique - <?= htmlspecialchars($concession['nom_zone'] ?: 'Concession') ?></title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            
            <style>
                @media print {
                    .no-print { display: none !important; }
                    body { font-size: 12px; }
                }
                
                .header-section {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 20px;
                    border-radius: 10px;
                    margin-bottom: 20px;
                }
                
                .info-row {
                    border-bottom: 1px solid #eee;
                    padding: 8px 0;
                }
                
                .info-row:last-child {
                    border-bottom: none;
                }
                
                .map-container {
                    height: 300px;
                    border: 2px solid #ddd;
                    border-radius: 8px;
                    margin: 10px 0;
                }
                
                .coordinates-box {
                    background: #f8f9fa;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    padding: 15px;
                    font-family: 'Courier New', monospace;
                    font-size: 0.9rem;
                }
                
                .logo-section {
                    text-align: right;
                    margin-bottom: 20px;
                }
                
                .footer-section {
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 2px solid #667eea;
                    text-align: center;
                    color: #666;
                    font-size: 0.9rem;
                }
            </style>
        </head>
        <body>
            <div class="container-fluid">
                <!-- Header with logo space -->
                <div class="row">
                    <div class="col-8">
                        <div class="header-section">
                            <h1><i class="fas fa-fish me-3"></i>FICHE TECHNIQUE DE CONCESSION</h1>
                            <h3><?= htmlspecialchars($concession['nom_zone'] ?: 'Concession Aquaculture') ?></h3>
                            <p class="mb-0">Code: <strong><?= htmlspecialchars($concession['code_concession'] ?: 'N/A') ?></strong></p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="logo-section">
                            <!-- Map reference will be here -->
                            <div id="map-reference" class="map-container"></div>
                            <small class="text-muted">Localisation de référence</small>
                        </div>
                    </div>
                </div>
                
                <!-- Main content -->
                <div class="row">
                    <div class="col-6">
                        <h4><i class="fas fa-info-circle me-2"></i>Informations Générales</h4>
                        
                        <div class="info-row">
                            <strong><i class="fas fa-hashtag me-2"></i>ID:</strong> 
                            <?= htmlspecialchars($concession['id']) ?>
                        </div>
                        
                        <div class="info-row">
                            <strong><i class="fas fa-map-marker-alt me-2"></i>Wilaya:</strong> 
                            <?= htmlspecialchars($concession['wilaya_name_ascii'] ?: 'N/A') ?>
                        </div>
                        
                        <div class="info-row">
                            <strong><i class="fas fa-building me-2"></i>Commune:</strong> 
                            <?= htmlspecialchars($concession['commune_name_ascii'] ?: 'N/A') ?>
                        </div>
                        
                        <?php if ($concession['superficie']): ?>
                        <div class="info-row">
                            <strong><i class="fas fa-expand-arrows-alt me-2"></i>Superficie:</strong> 
                            <?= number_format($concession['superficie'], 2) ?> m²
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($concession['distance_voi_acces']): ?>
                        <div class="info-row">
                            <strong><i class="fas fa-road me-2"></i>Distance voie d'accès:</strong> 
                            <?= $concession['distance_voi_acces'] ?>m
                        </div>
                        <?php endif; ?>
                        
                        <div class="info-row">
                            <strong><i class="fas fa-calendar me-2"></i>Date de création:</strong> 
                            <?= date('d/m/Y à H:i', strtotime($concession['created_at'])) ?>
                        </div>
                        
                        <div class="info-row">
                            <strong><i class="fas fa-eye me-2"></i>Statut:</strong> 
                            <?= $concession['visible'] ? 'Actif' : 'Inactif' ?>
                        </div>
                        
                        <?php if ($concession['description']): ?>
                        <div class="info-row">
                            <strong><i class="fas fa-file-text me-2"></i>Description:</strong><br>
                            <?= nl2br(htmlspecialchars($concession['description'])) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-6">
                        <h4><i class="fas fa-crosshairs me-2"></i>Coordonnées Géographiques</h4>
                        <p class="text-muted">Carré de 50m x 50m</p>
                        
                        <div class="coordinates-box">
                            <div class="row">
                                <div class="col-6">
                                    <strong>A (Nord-Ouest):</strong><br>
                                    <?= htmlspecialchars($concession['coordonnee_a']) ?>
                                </div>
                                <div class="col-6">
                                    <strong>B (Nord-Est):</strong><br>
                                    <?= htmlspecialchars($concession['coordonnee_b']) ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <strong>D (Sud-Ouest):</strong><br>
                                    <?= htmlspecialchars($concession['coordonnee_d']) ?>
                                </div>
                                <div class="col-6">
                                    <strong>C (Sud-Est):</strong><br>
                                    <?= htmlspecialchars($concession['coordonnee_c']) ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <strong>Format:</strong> <?= htmlspecialchars($concession['format_coordonnees'] ?: 'decimal') ?><br>
                            <strong>Centre approximatif:</strong> <?= number_format($centerLat, 6) ?>, <?= number_format($centerLng, 6) ?>
                        </div>
                        
                        <div class="mt-3">
                            <h5><i class="fas fa-ruler me-2"></i>Dimensions</h5>
                            <div class="info-row">
                                <strong>Longueur:</strong> 50 mètres
                            </div>
                            <div class="info-row">
                                <strong>Largeur:</strong> 50 mètres
                            </div>
                            <div class="info-row">
                                <strong>Superficie totale:</strong> 2,500 m²
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="footer-section">
                    <p><strong>Système de Gestion des Concessions Aquaculture</strong></p>
                    <p>Document généré le <?= date('d/m/Y à H:i:s') ?></p>
                    <p><i class="fas fa-print me-2"></i>Ce document peut être imprimé pour archivage</p>
                </div>
                
                <!-- Print button -->
                <div class="text-center mt-4 no-print">
                    <button onclick="window.print()" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-print me-2"></i>Imprimer / Sauvegarder en PDF
                    </button>
                    <button onclick="window.close()" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times me-2"></i>Fermer
                    </button>
                </div>
            </div>

            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script>
                // Create small reference map
                const coords = <?= json_encode($coords) ?>;
                const centerLat = <?= $centerLat ?>;
                const centerLng = <?= $centerLng ?>;
                
                const refMap = L.map('map-reference').setView([centerLat, centerLng], 15);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(refMap);
                
                // Add marker
                const customIcon = L.icon({
                    iconUrl: 'icon.png',
                    iconSize: [30, 45],
                    iconAnchor: [15, 45]
                });
                
                L.marker([centerLat, centerLng], {
                    icon: customIcon
                }).addTo(refMap);
                
                // Add square
                L.polygon(coords, {
                    color: '#28a745',
                    weight: 2,
                    fillOpacity: 0.3
                }).addTo(refMap);
                
                // Auto-print after a short delay to allow map to load
                setTimeout(() => {
                    // Uncomment the next line to auto-print
                    // window.print();
                }, 2000);
            </script>
        </body>
        </html>
        <?php
        
    } catch (Exception $e) {
        die('Erreur: ' . htmlspecialchars($e->getMessage()));
    }
    
} else {
    /**
 * Helper function to create table rows
 */
function addTableRow($label, $value, $isMultiLine = false) {
    $html = '<tr>';
    $html .= '<td width="30%" style="font-weight:bold;">' . $label . ':</td>';
    $html .= '<td width="70%">' . ($isMultiLine ? nl2br($value) : $value) . '</td>';
    $html .= '</tr>';
    return $html;
}

// Get concession ID from POST or GET
$concessionId = $_POST['concession_id'] ?? $_GET['id'] ?? null;

if (!$concessionId) {
    die('ID de concession manquant');
}

try {
    // Create database connection
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Get concession data
    $stmt = $pdo->prepare("SELECT * FROM coordinates WHERE id = :id");
    $stmt->execute([':id' => $concessionId]);
    $concession = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$concession) {
        die('Concession non trouvée');
    }
    
    // Parse coordinates
    $coords = [];
    foreach (['coordonnee_a', 'coordonnee_b', 'coordonnee_c', 'coordonnee_d'] as $coord) {
        $parts = preg_split('/[\s,]+/', $concession[$coord]);
        $coords[] = [floatval($parts[0]), floatval($parts[1])];
    }
    $centerLat = array_sum(array_column($coords, 0)) / 4;
    $centerLng = array_sum(array_column($coords, 1)) / 4;
    
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('Système de Gestion des Concessions Aquaculture');
    $pdf->SetAuthor('Ministère de la Pêche');
    $pdf->SetTitle('Fiche Technique - ' . $concession['nom_zone']);
    $pdf->SetSubject('Fiche Technique de Concession Aquacole');
    
    // Set default header data
    $pdf->SetHeaderData('', 0, 'Fiche Technique de Concession', 'Système de Gestion des Concessions Aquaculture');
    
    // Set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    // Set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
    // Add a page
    $pdf->AddPage();
    
    // Set font
    $pdf->SetFont('helvetica', 'B', 16);
    
    // Title
    $pdf->Cell(0, 10, 'FICHE TECHNIQUE DE CONCESSION', 0, 1, 'C');
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, $concession['nom_zone'], 0, 1, 'C');
    
    // Add logo if available
    $logo = 'logo.png'; // Make sure to place your logo in the same directory
    if (file_exists($logo)) {
        $pdf->Image($logo, 15, 15, 30, 0, '', '', '', false, 300, '', false, false, 0);
    }
    
    // Add code badge
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Ln(5);
    $pdf->Cell(0, 10, 'Code: ' . $concession['code_concession'], 0, 1, 'R');
    
    // Line break
    $pdf->Ln(10);
    
    // Add date
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, 'Généré le: ' . date('d/m/Y à H:i:s'), 0, 1, 'R');
    
    // Add a line
    $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
    $pdf->Ln(10);
    
    // Set font for content
    $pdf->SetFont('helvetica', '', 10);
    
    // Create two columns
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Informations Générales', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);
    
    // Create table for general information
    $html = '<table cellspacing="0" cellpadding="4" border="0.5">';
    
    // Add rows with concession data
    $html .= addTableRow('ID', $concession['id']);
    $html .= addTableRow('Wilaya', $concession['wilaya_name_ascii']);
    $html .= addTableRow('Commune', $concession['commune_name_ascii']);
    
    if ($concession['superficie']) {
        $html .= addTableRow('Superficie', number_format($concession['superficie'], 2) . ' m²');
    }
    
    if ($concession['distance_voi_acces']) {
        $html .= addTableRow('Distance voie d\'accès', $concession['distance_voi_acces'] . ' mètres');
    }
    
    $html .= addTableRow('Date de création', date('d/m/Y à H:i', strtotime($concession['created_at'])));
    $html .= addTableRow('Statut', $concession['visible'] ? 'Actif' : 'Inactif');
    
    if ($concession['description']) {
        $html .= addTableRow('Description', $concession['description'], true);
    }
    
    $html .= '</table>';
    
    // Output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');
    
    // Add coordinates section
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Coordonnées Géographiques (50m x 50m)', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);
    
    // Create table for coordinates
    $coord_html = '<table cellspacing="0" cellpadding="4" border="0.5">';
    $coord_html .= addTableRow('Point A (Nord-Ouest)', $concession['coordonnee_a']);
    $coord_html .= addTableRow('Point B (Nord-Est)', $concession['coordonnee_b']);
    $coord_html .= addTableRow('Point C (Sud-Est)', $concession['coordonnee_c']);
    $coord_html .= addTableRow('Point D (Sud-Ouest)', $concession['coordonnee_d']);
    $coord_html .= addTableRow('Format', $concession['format_coordonnees'] ?: 'decimal');
    $coord_html .= addTableRow('Centre approximatif', number_format($centerLat, 6) . ', ' . number_format($centerLng, 6));
    $coord_html .= '</table>';
    
    // Output the coordinates table
    $pdf->writeHTML($coord_html, true, false, true, false, '');
    
    // Add footer
    $pdf->SetY(-15);
    $pdf->SetFont('helvetica', 'I', 8);
    $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    
    // Close and output PDF document
    $pdf->Output('Fiche_Technique_' . $concession['code_concession'] . '.pdf', 'D');
    
} catch (Exception $e) {
    die('Erreur: ' . $e->getMessage());
}

}
?>
