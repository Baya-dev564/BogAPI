<?php
// BlogAPI - Point d'entrée principal avec routing
// Développé par Baya AMELLAL PAYAN

// Je active l'affichage des erreurs pour le développement
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

// Je charge les classes nécessaires
require_once '../config/Database.php';
require_once '../app/core/Router.php';
require_once '../app/models/User.php';
require_once '../app/models/Article.php';
require_once '../app/models/Category.php';
require_once '../app/controllers/HomeController.php';
require_once '../app/controllers/UserController.php';
require_once '../app/controllers/ArticleController.php';
require_once '../app/controllers/CategoryController.php';

try {
    // Je initialise la base de données
    $database = new Database();
    
    // Je crée le router
    $router = new Router($database);

    // === DÉFINITION DES ROUTES API ===
    
    // Routes GET des articles
    $router->get('/api/articles', 'ArticleController', 'getAll');
    $router->get('/api/articles/{slug}', 'ArticleController', 'getBySlug');
    
    // Route POST pour créer un article
    $router->post('/api/articles', 'ArticleController', 'create');
    
    // Routes des utilisateurs  
    $router->get('/api/users', 'UserController', 'getAll');
    $router->get('/api/users/{id}', 'UserController', 'getById');
    
    // Routes des catégories
    $router->get('/api/categories', 'CategoryController', 'getAll');
    $router->get('/api/categories/count', 'CategoryController', 'getWithCount');
    $router->get('/api/categories/{slug}', 'CategoryController', 'getBySlug');
    
    // Route par défaut (info API)
    $router->get('/', 'HomeController', 'index');

    // Je traite la requête
    $router->dispatch();

} catch (Exception $e) {
    // Je gère les erreurs globales
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de serveur',
        'error' => $e->getMessage()
    ]);
}
?>
