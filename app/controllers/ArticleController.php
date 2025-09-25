<?php
// BlogAPI - Contrôleur des articles
// Développé par Baya AMELLAL PAYAN

class ArticleController {
    private $db;
    private $article;

    public function __construct($database) {
        $this->db = $database;
        $this->article = new Article($this->db);
    }

    public function getAll() {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $offset = ($page - 1) * $limit;

            $articles = $this->article->getAll($limit, $offset);

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'data' => $articles,
                'pagination' => ['page' => $page, 'limit' => $limit, 'total' => count($articles)]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la récupération des articles', 'error' => $e->getMessage()]);
        }
    }

    public function getBySlug($slug) {
        try {
            $article = $this->article->getBySlug($slug);
            if ($article) {
                $this->article->incrementViews($article['id']);
                http_response_code(200);
                echo json_encode(['success' => true, 'data' => $article]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Article non trouvé']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la récupération de l\'article', 'error' => $e->getMessage()]);
        }
    }
}
