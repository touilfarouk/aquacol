<?php
require_once('config.php');

echo "Starting database setup...\n";

try {
    // Connect to MySQL without selecting a database
    $pdo = new PDO("mysql:host=" . DB_SERVER, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '" . DB_NAME . "' created or already exists.\n";
    
    // Select the database
    $pdo->exec("USE `" . DB_NAME . "`");
    
    // Read and execute the schema file
    $schema = file_get_contents('database_schema.sql');
    $pdo->exec($schema);
    echo "Database schema created successfully.\n";
    
    // Import minimal data
    echo "Now importing minimal data...\n";
    require_once('import_minimal_data.php');
    
    echo "\nDatabase setup completed successfully!\n";
    echo "You can now access the application at: http://localhost/aquaculture/\n";
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage() . "\n");
} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}
?>
