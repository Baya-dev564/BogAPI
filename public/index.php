<?php
// BlogAPI - Point d'entrée principal
// Développé par Baya AMELLAL PAYAN

// Je active l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Je configure les headers pour l'API REST
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Je gère les requêtes OPTIONS (preflight CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Je renvoie les informations de base de l'API
echo json_encode([
    'message' => 'BlogAPI - Développé par Baya AMELLAL PAYAN',
    'version' => '1.0.0',
    'status' => 'active',
    'php_version' => phpversion()
]);
?>
