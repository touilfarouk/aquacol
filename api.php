<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

include('config.php');

// Decode the JSON input into an associative array
$form = json_decode(file_get_contents("php://input"), true);

if (!$form || !isset($form['form'])) {
    echo json_encode(["response" => "false", "message" => "Invalid JSON data"]);
    exit;
}

$data = $form['form'];

try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]);

    // Generate concession code
    $codeWilaya = $data['code_wilaya'] ?? '';
    $codeCommune = $data['code_commune'] ?? '';
    $codeConcession = $codeWilaya . $codeCommune . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

    // Insert Query 
    $query = "INSERT INTO coordinates (nom_zone,zone, wilaya_name_ascii, commune_name_ascii, code_wilaya, code_commune, visible, distance_voi_acces, code_concession, superficie, description, coordonnee_a, coordonnee_b, coordonnee_c, coordonnee_d, format_coordonnees, created_at) 
              VALUES (:nom_zone, :zone, :wilaya_name_ascii, :commune_name_ascii, :code_wilaya, :code_commune, :visible, :distance_voi_acces, :code_concession, :superficie, :description, :coordonnee_a, :coordonnee_b, :coordonnee_c, :coordonnee_d, :format_coordonnees, NOW())";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':nom_zone' => $data['nom_zone'] ?? '',
        ':zone' => $data['zone'] ?? '',
        ':wilaya_name_ascii' => $data['wilaya_name_ascii'] ?? '',
        ':commune_name_ascii' => $data['commune_name_ascii'] ?? '',
        ':code_wilaya' => $codeWilaya,
        ':code_commune' => $data['code_commune'] ?? '',
        ':visible' => 1, // Default to visible
        ':distance_voi_acces' => $data['distance_voi_acces'] ?? null,
        ':code_concession' => $codeConcession,
        ':superficie' => $data['superficie'] ?? null,
        ':description' => $data['description'] ?? '',
        ':coordonnee_a' => $data['coordonnee_a'] ?? '',
        ':coordonnee_b' => $data['coordonnee_b'] ?? '',
        ':coordonnee_c' => $data['coordonnee_c'] ?? '',
        ':coordonnee_d' => $data['coordonnee_d'] ?? '',
        ':format_coordonnees' => $data['format_coordonnees'] ?? 'decimal'
    ]);

    echo json_encode(['response' => "true", 'message' => 'Data inserted successfully', 'id' => $pdo->lastInsertId()]);
} catch (Exception $e) {
    echo json_encode(["response" => "false", "message" => $e->getMessage()]);
}
?>
