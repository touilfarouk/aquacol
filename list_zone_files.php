<?php
// list_zone_files.php - Dynamically list JSON files in the zone folder
header('Content-Type: application/json');

// Path to the zone folder
$zoneFolder = __DIR__ . '/zone/';

// Check if the folder exists
if (!is_dir($zoneFolder)) {
    echo json_encode([]);
    exit;
}

// Get all JSON files in the zone folder
$files = scandir($zoneFolder);
$jsonFiles = [];

foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
        $jsonFiles[] = $file;
    }
}

// Return the list of JSON files
echo json_encode($jsonFiles);
?>
