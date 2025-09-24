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
    
    // Minimal wilayas data
    $wilayas = [
        ['01', 'Adrar', 'Adrar', 27.8746, -0.2938],
        ['16', 'Alger', 'Alger', 36.7538, 3.0588],
        ['31', 'Oran', 'Oran', 35.7044, -0.6503],
        ['19', 'Sétif', 'Setif', 36.1910, 5.4137],
        ['25', 'Constantine', 'Constantine', 36.3650, 6.6147]
    ];
    
    // Insert wilayas
    $wilayaStmt = $pdo->prepare("INSERT INTO wilayas (code, name, name_ascii, latitude, longitude) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($wilayas as $wilaya) {
        $wilayaStmt->execute($wilaya);
        echo "Inserted wilaya: " . $wilaya[1] . "\n";
    }
    
    // Minimal communes data (2-3 communes per wilaya)
    $communes = [
        // Adrar
        [1, '0101', 'Adrar', 'Adrar', 27.8746, -0.2938],
        [1, '0102', 'Tamest', 'Tamest', 27.9, -0.25],
        
        // Alger
        [2, '1601', 'Alger Centre', 'Alger Centre', 36.7764, 3.0586],
        [2, '1602', 'Sidi M\'Hamed', 'Sidi Mhamed', 36.7594, 3.0572],
        
        // Oran
        [3, '3101', 'Oran', 'Oran', 35.7044, -0.6503],
        [3, '3102', 'Es Senia', 'Es Senia', 35.6459, -0.6196],
        
        // Sétif
        [4, '1901', 'Sétif', 'Setif', 36.1910, 5.4137],
        [4, '1902', 'El Eulma', 'El Eulma', 36.1500, 5.6833],
        
        // Constantine
        [5, '2501', 'Constantine', 'Constantine', 36.3650, 6.6147],
        [5, '2502', 'El Khroub', 'El Khroub', 36.2639, 6.6936]
    ];
    
    // Insert communes
    $communeStmt = $pdo->prepare("INSERT INTO communes (wilaya_id, code, name, name_ascii, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
    
    foreach ($communes as $commune) {
        $communeStmt->execute($commune);
        echo "Inserted commune: " . $commune[2] . " (Wilaya ID: " . $commune[0] . ")\n";
    }
    
    echo "Minimal data import completed successfully!\n";
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
