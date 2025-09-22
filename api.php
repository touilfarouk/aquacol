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
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Insert Query 
    $query = "INSERT INTO coordinates (nom_zone, description, coordonnee_a, coordonnee_b, coordonnee_c, coordonnee_d, format_coordonnees, created_at) 
              VALUES (:nom_zone, :description, :coordonnee_a, :coordonnee_b, :coordonnee_c, :coordonnee_d, :format_coordonnees, NOW())";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':nom_zone' => $data['nom_zone'] ?? '',
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
