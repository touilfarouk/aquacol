<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

$data = json_decode(file_get_contents('php://input'), true);
$filename = $data['filename'] ?? '';

// Validate filename
if (!preg_match('/^[a-z0-9_]+\.json$/', $filename)) {
    die(json_encode(['success' => false, 'message' => 'Invalid filename']));
}

$filepath = __DIR__ . '/zone/' . $filename;

if (file_exists($filepath) && unlink($filepath)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting file']);
}
