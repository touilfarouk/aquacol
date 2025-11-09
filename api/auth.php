<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET");

header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On interdit toute méthode qui n'est pas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //http_response_code(405);
    die(json_encode(['message' => 'Méthode non autorisée']));
}

$data = json_decode(file_get_contents("php://input"));

// message :
// 0 => Token introuvable
// 1 => Token invalide
// 2=> Le token est invalide
// 3 => Le token a expiré



$token = $data->token;

// On vérifie si la chaine commence par "Bearer "
if (!isset($token)) {
    //http_response_code(400);
    die(json_encode(['message' => 'Token introuvable']));
}

// On extrait le token


require_once 'includes/config.php';
require_once 'classes/JWT.php';

$jwt = new JWT();

// On vérifie la validité
if (!$jwt->isValid($token)) {
    // http_response_code(400);
    die(json_encode(['message' => 'Token invalide']));
}

// On vérifie la signature
if (!$jwt->check($token, SECRET)) {
    // http_response_code(403);
    die(json_encode(['message' => 'Le token est invalide']));
}

// On vérifie l'expiration
if ($jwt->isExpired($token)) {
    // http_response_code(403);
    die(json_encode(['message' => 'Le token a expiré']));
}
