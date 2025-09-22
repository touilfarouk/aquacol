<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

include('config.php');

try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Get all coordinates
    $query = "SELECT * FROM coordinates ORDER BY created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    $coordinates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['response' => "true", 'data' => $coordinates]);
} catch (Exception $e) {
    echo json_encode(["response" => "false", "message" => $e->getMessage()]);
}
?>
