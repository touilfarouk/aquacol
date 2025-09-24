<?php
// Redirect helper for backward compatibility
// Converts old URLs to new secure format with concession code

$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? 'details';

if (!$id) {
    header("Location: index.html", true, 302);
    exit;
}

try {
    // Database connection
    require_once('config.php');
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Get concession code
    $stmt = $pdo->prepare("SELECT code_concession FROM coordinates WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && $result['code_concession']) {
        // Redirect to secure URL format
        if ($type === 'application') {
            $secureUrl = "application/" . $result['code_concession'] . "-" . $id;
        } else {
            // For details, use direct format without "details/"
            $secureUrl = $result['code_concession'] . "-" . $id;
        }
        header("Location: $secureUrl", true, 301);
        exit;
    } else {
        // If no code found, redirect to index
        header("Location: index.html", true, 302);
        exit;
    }
    
} catch (Exception $e) {
    // On error, redirect to index
    header("Location: index.html", true, 302);
    exit;
}
?>
