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
    $stmt = $pdo->prepare("SELECT * FROM coordinates WHERE id = :id");
    $stmt->execute([':id' => $concessionId]);
    $concession = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$concession) {
        die('Concession non trouvée');
    }
    
    // Load RIBs data
    $ribsJson = file_get_contents('ribs.json');
    $ribsData = json_decode($ribsJson, true);
    $ribs = [];

    // Extract the actual data from the nested structure
    $actualRibsData = null;
    foreach ($ribsData as $item) {
        if (is_array($item) && isset($item['data']) && is_array($item['data'])) {
            $actualRibsData = $item['data'];
            break;
        }
    }

    // Find all RIBs for the wilaya
    if ($actualRibsData && is_array($actualRibsData)) {
        foreach ($actualRibsData as $item) {
            if (is_array($item) && isset($item['code_wilaya']) && $item['code_wilaya'] === $concession['code_wilaya']) {
                $ribs[] = $item;
            }
        }
    }

    // If no RIB found for the wilaya, use the first available one
    if (empty($ribs) && $actualRibsData && is_array($actualRibsData) && !empty($actualRibsData)) {
        $ribs[] = $actualRibsData[0];
    }

    // Debug: Log RIB data for troubleshooting
    error_log("Concession code_wilaya: " . $concession['code_wilaya']);
    error_log("Number of RIBs found: " . count($ribs));
    error_log("RIBs data: " . json_encode($ribs));
    
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('Système de Gestion des Concessions Aquaculture');
    $pdf->SetAuthor('Ministère de la Pêche');
    $pdf->SetTitle('Bordereau de Paiement - ' . $concession['code_concession']);
    $pdf->SetSubject('Bordereau de Paiement');
    
    // Set default header data
    $pdf->SetHeaderData('', 0, 'Bordereau de Paiement', 'Système de Gestion des Concessions Aquaculture');
    
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
    $pdf->Cell(0, 10, 'BORDEREAU DE PAIEMENT', 0, 1, 'C');
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Concession: ' . $concession['code_concession'], 0, 1, 'C');
    
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
    $info_html .= addTableRow('Code Concession', $concession['code_concession']);
    $info_html .= addTableRow('Nom de la Zone', $concession['nom_zone']);
    $info_html .= addTableRow('Wilaya', $concession['wilaya_name_ascii']);
    $info_html .= addTableRow('Commune', $concession['commune_name_ascii']);
    if ($concession['superficie']) {
        $info_html .= addTableRow('Superficie', number_format($concession['superficie'], 2) . ' m²');
    }
    $info_html .= '</table>';
    
    $pdf->writeHTML($info_html, true, false, true, false, '');
    
    // Add payment information
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Détails du Paiement', 0, 1, 'L');
    
    // Create payment table
    $payment_html = '<table cellspacing="0" cellpadding="5" border="1" style="border-collapse: collapse;">';
    $payment_html .= '<tr style="background-color:#f2f2f2;">';
    $payment_html .= '<th width="15%" style="font-weight:bold; text-align:center;">Quantité</th>';
    $payment_html .= '<th width="70%" style="font-weight:bold; text-align:center;">Désignation</th>';
    $payment_html .= '<th width="15%" style="font-weight:bold; text-align:right;">Montant (DZD)</th>';
    $payment_html .= '</tr>';
    
    // Add payment items
    $payment_html .= '<tr>';
    $payment_html .= '<td style="text-align:center;">1</td>';
    $payment_html .= '<td>Droit de concession aquacole</td>';
    $payment_html .= '<td style="text-align:right;">20 000,00</td>';
    $payment_html .= '</tr>';
    
    // Add total
    $payment_html .= '<tr style="background-color:#f2f2f2;">';
    $payment_html .= '<td colspan="2" style="text-align:right; font-weight:bold;">Total à payer:</td>';
    $payment_html .= '<td style="text-align:right; font-weight:bold;">20 000,00</td>';
    $payment_html .= '</tr>';
    
    $payment_html .= '</table>';
    
    $pdf->writeHTML($payment_html, true, false, true, false, '');
    
    // Add bank information
    if (!empty($ribs)) {
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Coordonnées Bancaires', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);

        $bank_html = '<table cellspacing="0" cellpadding="4" border="0.5">';
        foreach ($ribs as $rib) {
            if (is_array($rib) && isset($rib['rib']) && !empty($rib['rib'])) {
                $bank_html .= addTableRow('Structure', $rib['structure_onta'] ?? 'N/A');
                $bank_html .= addTableRow('RIB', $rib['rib']);
                $bank_html .= '<tr><td colspan="2" style="height:10px;"></td></tr>';
            }
        }
        $bank_html .= '</table>';

        $pdf->writeHTML($bank_html, true, false, true, false, '');
    } else {
        // Show a message if no RIB data is available
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Coordonnées Bancaires', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 10, 'Aucune information bancaire disponible pour cette wilaya.', 0, 1, 'L');
    }
    
    // Add payment instructions
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Instructions de Paiement', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);
    
    $instructions = '1. Effectuez le paiement de 20 000,00 DZD à l\'un des comptes bancaires indiqués ci-dessus.' . "\n";
    $instructions .= '2. Utilisez le code de concession (' . $concession['code_concession'] . ') comme référence de paiement.' . "\n";
    $instructions .= '3. Conservez une copie de ce bordereau et du reçu de paiement comme justificatif.';
    
    $pdf->MultiCell(0, 10, $instructions, 0, 'L', false, 1);
    
    // Add footer
    $pdf->SetY(-15);
    $pdf->SetFont('helvetica', 'I', 8);
    $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    
    // Close and output PDF document
    $pdf->Output('Paiement_' . $concession['code_concession'] . '.pdf', 'D');
    
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
?>
