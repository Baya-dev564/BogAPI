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

    /**
     * Je récupère tous les articles avec pagination
     */
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

    /**
     * Je récupère un article par son slug et j'incrémente les vues
     */
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

    /**
     * Je crée un nouvel article (POST /api/articles)
     */
  
public function create() {
    try {
        $data = [];
        
        // Je gère les données JSON ET les données de formulaire HTML
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if (strpos($contentType, 'application/json') !== false) {
            // Données JSON
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
        } else {
            // Données de formulaire HTML
            $data = [
                'title' => $_POST['title'] ?? '',
                'content' => $_POST['content'] ?? '',
                'category_id' => $_POST['category_id'] ?? 1,
                'author_id' => $_POST['author_id'] ?? 1
            ];
        }
        
        // Je valide que les données obligatoires sont présentes
        if (!$data || empty($data['title']) || empty($data['content'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Titre et contenu obligatoires'
            ]);
            return;
        }
        
        // Je génère un slug si pas fourni
        if (!isset($data['slug'])) {
            $data['slug'] = strtolower(str_replace(' ', '-', $data['title']));
        }
        
        // Je crée l'article via le model
        $articleId = $this->article->create($data);
        
        if ($articleId) {
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Article créé avec succès !',
                'data' => ['id' => $articleId]
            ]);
        } else {
            throw new Exception('Erreur lors de la création de l\'article');
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur serveur: ' . $e->getMessage()
        ]);
    }
}

    /**
     * Je modifie un article existant (PUT /api/articles/{id})
     */
    public function update($id) {
        try {
            // Je récupère les données JSON
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            // Je vérifie les données obligatoires
            if (!$data || !isset($data['title']) || !isset($data['content'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Titre et contenu obligatoires'
                ]);
                return;
            }
            
            // Je modifie l'article
            $result = $this->article->update($id, $data);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Article modifié avec succès'
                ]);
            } else {
                throw new Exception('Article non trouvé ou erreur de modification');
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Je supprime un article (DELETE /api/articles/{id})
     */
    public function delete($id) {
        try {
            // Je supprime l'article de la base de données
            $result = $this->article->delete($id);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Article supprimé avec succès'
                ]);
            } else {
                throw new Exception('Article non trouvé ou impossible à supprimer');
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
