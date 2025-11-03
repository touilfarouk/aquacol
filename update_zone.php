<?php
header('Content-Type: application/json');

try {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['file']) || !isset($data['coordinates'])) {
        throw new Exception('Invalid data received');
    }
    
    $file = 'zone/' . basename($data['file']);
    
    // Verify file exists and is in zone directory
    if (!file_exists($file) || !is_file($file)) {
        throw new Exception('Zone file not found');
    }
    
    // Read existing zone data
    $zoneData = json_decode(file_get_contents($file), true);
    
    // Update coordinates in first feature
    if (isset($zoneData['features'][0])) {
        $zoneData['features'][0]['geometry']['coordinates'][0] = $data['coordinates'];
        
        // Save updated file
        if (file_put_contents($file, json_encode($zoneData, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception('Failed to save file');
        }
    } else {
        throw new Exception('Invalid zone file structure');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
