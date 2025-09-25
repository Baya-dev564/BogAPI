<?php
// BlogAPI - Contrôleur des catégories
// Développé par Baya AMELLAL PAYAN

class CategoryController {
    private $db;
    private $category;

    public function __construct($database) {
        // Je récupère la connexion et j'initialise le modèle
        $this->db = $database;
        $this->category = new Category($this->db);
    }

    /**
     * Je récupère toutes les catégories
     */
    public function getAll() {
        try {
            // Je récupère toutes les catégories actives
            $categories = $this->category->getAll();

            // Je retourne la réponse JSON
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'data' => $categories,
                'count' => count($categories)
            ]);

        } catch (Exception $e) {
            // Je gère les erreurs
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la récupération des catégories',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Je récupère les catégories avec le nombre d'articles
     */
    public function getWithCount() {
        try {
            // Je récupère les catégories avec comptage
            $categories = $this->category->getWithArticleCount();

            // Je retourne la réponse JSON
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'data' => $categories,
                'count' => count($categories)
            ]);

        } catch (Exception $e) {
            // Je gère les erreurs
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la récupération des catégories',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Je récupère une catégorie par son slug
     */
    public function getBySlug($slug) {
        try {
            // Je valide le slug
            if (empty($slug)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Slug de catégorie requis'
                ]);
                return;
            }

            // Je récupère la catégorie
            $category = $this->category->getBySlug($slug);

            if ($category) {
                // Je retourne la catégorie
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'data' => $category
                ]);
            } else {
                // Catégorie non trouvée
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Catégorie non trouvée'
                ]);
            }

        } catch (Exception $e) {
            // Je gère les erreurs
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la catégorie',
                'error' => $e->getMessage()
            ]);
        }
    }
}
