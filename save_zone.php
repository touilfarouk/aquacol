<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

$data = $_POST;
$coordinates = [];

// Validate and combine lat/lng arrays into coordinates
if (isset($data['lat']) && isset($data['lng'])) {
    for ($i = 0; $i < count($data['lat']); $i++) {
        $coordinates[] = [
            round(floatval($data['lng'][$i]), 6),
            round(floatval($data['lat'][$i]), 6)
        ];
    }
    
    // Close the polygon by adding the first point again
    if (!empty($coordinates)) {
        $coordinates[] = $coordinates[0];
    }
}

// Create GeoJSON structure
$geojson = [
    'type' => 'FeatureCollection',
    'features' => [
        [
            'type' => 'Feature',
            'properties' => [
                'nom_zaa' => $data['nom_zaa'],
                'commune' => $data['commune'],
                'wilaya' => $data['wilaya'],
                'superficie_ha' => round(floatval($data['superficie_ha']), 2),
                'description' => $data['description']
            ],
            'geometry' => [
                'type' => 'Polygon',
                'coordinates' => [$coordinates]
            ]
        ]
    ]
];

// Generate filename from nom_zaa
$filename = preg_replace('/[^a-z0-9]+/', '_', strtolower($data['nom_zaa'])) . '.json';
$filepath = __DIR__ . '/zone/' . $filename;

// Ensure zone directory exists
if (!is_dir(__DIR__ . '/zone')) {
    mkdir(__DIR__ . '/zone', 0777, true);
}

// Save the file
if (file_put_contents($filepath, json_encode($geojson, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'message' => 'Zone enregistrée']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur d\'enregistrement']);
}
?>
