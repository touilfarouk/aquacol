<?php
header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['file']) || !isset($input['data'])) {
        throw new Exception('Missing required data');
    }
    
    $file = basename($input['file']); // Sanitize filename
    $path = __DIR__ . '/zone/' . $file;
    
    // Verify file exists and is in zone directory
    if (!file_exists($path) || dirname(realpath($path)) !== realpath(__DIR__ . '/zone')) {
        throw new Exception('Invalid file path');
    }
    
    // Save the updated GeoJSON
    if (file_put_contents($path, json_encode($input['data'], JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Failed to save file');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
