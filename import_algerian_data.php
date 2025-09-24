<?php
require_once('config.php');

// Create database connection
try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "Connected to database successfully.\n";
    
    // Load wilayas data from JSON file
    $wilayasJson = file_get_contents('https://raw.githubusercontent.com/azouaoui-med/geospatial-algeria/master/wilayas.json');
    $wilayasData = json_decode($wilayasJson, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error decoding wilayas JSON: ' . json_last_error_msg());
    }
    
    // Insert wilayas
    $wilayaStmt = $pdo->prepare("INSERT INTO wilayas (code, name, name_ascii, latitude, longitude) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($wilayasData as $wilaya) {
        $wilayaStmt->execute([
            $wilaya['wilaya_code'],
            $wilaya['wilaya_name'],
            $wilaya['wilaya_name_ascii'],
            $wilaya['wilaya_lat'],
            $wilaya['wilaya_long']
        ]);
        echo "Inserted wilaya: " . $wilaya['wilaya_name'] . "\n";
    }
    
    // Load communes data from JSON file
    $communesJson = file_get_contents('https://raw.githubusercontent.com/azouaoui-med/geospatial-algeria/master/communes.json');
    $communesData = json_decode($communesJson, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error decoding communes JSON: ' . json_last_error_msg());
    }
    
    // Insert communes
    $communeStmt = $pdo->prepare("INSERT INTO communes (wilaya_id, code, name, name_ascii, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
    
    foreach ($communesData as $commune) {
        $communeStmt->execute([
            $commune['wilaya_id'],
            $commune['commune_id'],
            $commune['commune_name'],
            $commune['commune_name_ascii'],
            $commune['commune_lat'],
            $commune['commune_long']
        ]);
        echo "Inserted commune: " . $commune['commune_name'] . " (Wilaya ID: " . $commune['wilaya_id'] . ")\n";
    }
    
    echo "Data import completed successfully!\n";
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
