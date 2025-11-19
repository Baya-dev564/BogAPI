<?php
// BlogAPI - Contrôleur de la page d'accueil
// Développé par Baya AMELLAL PAYAN
require_once '../app/controllers/HomeController.php';

class HomeController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Je affiche les informations de l'API
     */
    public function index() {
        echo json_encode([
            'message' => 'BlogAPI - REST API développée par Baya AMELLAL PAYAN',
            'version' => '1.0.0',
            'status' => 'active',
            'endpoints' => [
                'articles' => '/api/articles',
                'users' => '/api/users', 
                'categories' => '/api/categories'
            ]
        ]);
    }
}
