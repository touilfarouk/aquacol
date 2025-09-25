<?php
require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get table structure
    $stmt = $pdo->query("SHOW COLUMNS FROM coordinates");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get first row to see sample data
    $stmt = $pdo->query("SELECT * FROM coordinates LIMIT 1");
    $sample = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h2>Table Structure</h2>";
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
    echo "<h2>Sample Data</h2>";
    echo "<pre>";
    print_r($sample);
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
