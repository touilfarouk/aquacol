<?php
/**
 * API Router for Concessions - Procedural Style
 * Routes les requêtes vers les fonctions de concession
 */

require_once __DIR__ . '/../controllers/ConcessionFunctions.php';

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
            if ($path === '/map') {
                // GET /concessions/map - Données pour la carte
                handleGetConcessionsForMap();
            } elseif ($path === '/search') {
                // GET /concessions/search?q=query - Recherche
                handleSearchConcessions();
            } elseif ($path === '/statistics') {
                // GET /concessions/statistics - Statistiques
                handleGetConcessionStatistics();
            } elseif (preg_match('/^\/(\d+)$/', $path, $matches)) {
                // GET /concessions/{id} - Une concession spécifique
                handleGetConcessionById($matches[1]);
            } else {
                // GET /concessions - Toutes les concessions
                handleGetAllConcessions();
            }
            break;
            
        case 'POST':
            if ($path === '') {
                // POST /concessions - Créer une nouvelle concession
                handleCreateConcession();
            } elseif (preg_match('/^\/(\d+)\/status$/', $path, $matches)) {
                // POST /concessions/{id}/status - Changer le statut
                handleUpdateConcessionStatus($matches[1]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Endpoint non trouvé']);
            }
            break;
            
        case 'PUT':
            if (preg_match('/^\/(\d+)$/', $path, $matches)) {
                // PUT /concessions/{id} - Mettre à jour une concession
                handleUpdateConcession($matches[1]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Endpoint non trouvé']);
            }
            break;
            
        case 'DELETE':
            if (preg_match('/^\/(\d+)$/', $path, $matches)) {
                // DELETE /concessions/{id} - Supprimer une concession
                handleDeleteConcession($matches[1]);
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
