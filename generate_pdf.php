<?php
require_once('config.php');
require_once('TCPDF-6.7.5/tcpdf.php');

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
    $stmt = $pdo->prepare("
        SELECT * FROM coordinates 
        WHERE id = :id
    ");
    $stmt->execute([':id' => $concessionId]);
    $concession = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$concession) {
        die('Concession non trouvée');
    }
    
    // Parse coordinates for map
    function parseCoordinateString($coordStr) {
        $parts = preg_split('/[\s,]+/', $coordStr);
        return [floatval($parts[0]), floatval($parts[1])];
    }
    
    $coords = [
        parseCoordinateString($concession['coordonnee_a']),
        parseCoordinateString($concession['coordonnee_b']),
        parseCoordinateString($concession['coordonnee_c']),
        parseCoordinateString($concession['coordonnee_d'])
    ];
    
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
    $pdf->Cell(0, 10, 'FICHE TECHNIQUE DE CONCESSION AQUACOLE', 0, 1, 'C');
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Zone: ' . $concession['nom_zone'], 0, 1, 'C');
    
    // Add date
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, 'Date: ' . date('d/m/Y'), 0, 1, 'R');
    
    // Add a line
    $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
    $pdf->Ln(10);
    
    // Add concession information
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Informations de la Concession', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);
    
    $info_html = '<table cellspacing="0" cellpadding="4" border="0.5">';
    $info_html .= addTableRow('ID', $concession['id']);
    $info_html .= addTableRow('Code Concession', $concession['code_concession']);
    $info_html .= addTableRow('Nom de la Zone', $concession['nom_zone']);
    $info_html .= addTableRow('Wilaya', $concession['wilaya_name_ascii'] ?? 'Non spécifiée');
    $info_html .= addTableRow('Commune', $concession['commune_name_ascii'] ?? 'Non spécifiée');
    if ($concession['superficie']) {
        $info_html .= addTableRow('Superficie', number_format($concession['superficie'], 2) . ' m²');
    }
    if ($concession['distance_voi_acces']) {
        $info_html .= addTableRow('Distance voie d\'accès', $concession['distance_voi_acces'] . ' mètres');
    }
    $info_html .= addTableRow('Date de création', date('d/m/Y H:i', strtotime($concession['created_at'])));
    $info_html .= addTableRow('Statut', $concession['visible'] ? 'Actif' : 'Inactif');
    if ($concession['description']) {
        $info_html .= addTableRow('Description', $concession['description']);
    }
    $info_html .= '</table>';
    
    $pdf->writeHTML($info_html, true, false, true, false, '');
    
    // Add coordinates section
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Coordonnées Géographiques', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);
    
    $coords_html = '<table cellspacing="0" cellpadding="5" border="1" style="border-collapse: collapse;">';
    $coords_html .= '<tr style="background-color:#f2f2f2;">';
    $coords_html .= '<th width="30%" style="font-weight:bold; text-align:center;">Point</th>';
    $coords_html .= '<th width="35%" style="font-weight:bold; text-align:center;">Latitude</th>';
    $coords_html .= '<th width="35%" style="font-weight:bold; text-align:center;">Longitude</th>';
    $coords_html .= '</tr>';
    
    $labels = ['A (Nord-Ouest)', 'B (Nord-Est)', 'C (Sud-Est)', 'D (Sud-Ouest)'];
    for ($i = 0; $i < 4; $i++) {
        $coords_html .= '<tr>';
        $coords_html .= '<td style="text-align:center;">' . $labels[$i] . '</td>';
        $coords_html .= '<td style="text-align:center;">' . number_format($coords[$i][0], 6) . '</td>';
        $coords_html .= '<td style="text-align:center;">' . number_format($coords[$i][1], 6) . '</td>';
        $coords_html .= '</tr>';
    }
    
    $coords_html .= '<tr style="background-color:#f2f2f2;">';
    $coords_html .= '<td style="text-align:center; font-weight:bold;">Centre</td>';
    $coords_html .= '<td style="text-align:center; font-weight:bold;">' . number_format($centerLat, 6) . '</td>';
    $coords_html .= '<td style="text-align:center; font-weight:bold;">' . number_format($centerLng, 6) . '</td>';
    $coords_html .= '</tr>';
    $coords_html .= '</table>';
    
    $pdf->writeHTML($coords_html, true, false, true, false, '');
    
    // Add map image section
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Localisation', 0, 1, 'L');
    
    // Generate static map URL using OpenStreetMap
    $mapUrl = 'https://maps.geoapify.com/v1/staticmap';
    
    // Create a polygon string for the concession
    $polygonCoords = [];
    foreach ($coords as $coord) {
        $polygonCoords[] = $coord[1] . ',' . $coord[0]; // lon,lat format
    }
    // Close the polygon
    $polygonCoords[] = $coords[0][1] . ',' . $coords[0][0];
    $polygonString = implode('|', $polygonCoords);
    
    // Add markers for each corner
    $markers = [];
    $labels = ['A', 'B', 'C', 'D'];
    foreach ($coords as $index => $coord) {
        $markers[] = sprintf('lonlat:%s,%s;color:red;size:small;text:%s;textsize:12', 
            $coord[1], $coord[0], $labels[$index]);
    }
    $markersString = implode('|', $markers);
    
    // Build the map URL
    $staticMapUrl = sprintf(
        '%s?style=osm-carto&width=800&height=600&center=lonlat:%s,%s&zoom=16&marker=%s&geojson={"type":"Feature","properties":{},"geometry":{"type":"Polygon","coordinates":[[%s]]}}&apiKey=YOUR_API_KEY',
        $mapUrl,
        $centerLng,
        $centerLat,
        urlencode($markersString),
        implode('],[', $polygonCoords)
    );
    
    // Add the map image to the PDF
    $pdf->Image($staticMapUrl, 15, $pdf->GetY(), 180, 0, '', '', '', false, 300, '', false, false, 0);
    
    // Add a note about the map
    $pdf->SetY($pdf->GetY() + 5);
    $pdf->SetFont('helvetica', 'I', 8);
    $pdf->Cell(0, 5, 'Carte générée avec OpenStreetMap. Les points A, B, C, D représentent les coins de la concession.', 0, 1, 'C');
    
    // Add footer
    $pdf->SetY(-15);
    $pdf->SetFont('helvetica', 'I', 8);
    $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    
    // Output the PDF
    $pdf->Output('Fiche_Technique_' . $concession['code_concession'] . '.pdf', 'I');
    
} catch (Exception $e) {
    die('Erreur: ' . $e->getMessage());
}

/**
 * Helper function to create table rows
 */
function addTableRow($label, $value) {
    $html = '<tr>';
    $html .= '<td width="30%" style="font-weight:bold;">' . $label . ':</td>';
    $html .= '<td width="70%">' . $value . '</td>';
    $html .= '</tr>';
    return $html;
}
