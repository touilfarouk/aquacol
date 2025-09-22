<?php
/**
 * API Router for Demandes - Procedural Style
 * Routes les requêtes vers les fonctions de demande
 */

require_once __DIR__ . '/../controllers/DemandeFunctions.php';

// Headers CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Router les requêtes
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '';

try {
    switch ($method) {
        case 'GET':
            if (preg_match('/^\/(\d+)$/', $path, $matches)) {
                // GET /demandes/{id} - Une demande spécifique
                handleGetDemandeById($matches[1]);
            } else {
                // GET /demandes - Toutes les demandes
                handleGetAllDemandes();
            }
            break;
            
        case 'POST':
            if ($path === '') {
                // POST /demandes - Créer une nouvelle demande
                handleCreateDemande();
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Endpoint non trouvé']);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
            break;
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}
?>
