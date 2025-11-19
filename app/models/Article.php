<?php
// BlogAPI - Modèle article
// Développé par Baya AMELLAL PAYAN

class Article {
    // Je définis la table et les propriétés
    private $table = 'articles';
    private $db;
    
    public $id;
    public $title;
    public $slug;
    public $content;
    public $excerpt;
    public $featured_image;
    public $author_id;
    public $category_id;
    public $status;
    public $view_count;
    public $is_featured;
    public $meta_title;
    public $meta_description;
    public $published_at;
    public $created_at;
    public $updated_at;

    public function __construct($database) {
        // Je récupère la connexion à la base
        $this->db = $database->connect();
    }

    /**
     * Je récupère tous les articles publiés avec leurs auteurs et catégories
     */
    public function getAll($limit = 10, $offset = 0) {
        $query = "SELECT a.*, u.username as author_name, u.first_name, u.last_name, 
                         c.name as category_name, c.slug as category_slug
                  FROM {$this->table} a
                  JOIN users u ON a.author_id = u.id
                  JOIN categories c ON a.category_id = c.id
                  WHERE a.status = 'published'
                  ORDER BY a.published_at DESC
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Je récupère un article par son slug
     */
    public function getBySlug($slug) {
        $query = "SELECT a.*, u.username as author_name, u.first_name, u.last_name, u.avatar as author_avatar,
                         c.name as category_name, c.slug as category_slug
                  FROM {$this->table} a
                  JOIN users u ON a.author_id = u.id
                  JOIN categories c ON a.category_id = c.id
                  WHERE a.slug = :slug AND a.status = 'published'";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    /**
     * Je incrémente le compteur de vues
     */
    public function incrementViews($id) {
        $query = "UPDATE {$this->table} SET view_count = view_count + 1 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    /**
 * Je crée un nouvel article dans la base de données
 */
public function create($data) {
    try {
        // Je génère un slug si pas fourni
        if (!isset($data['slug']) && isset($data['title'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }
        
        $sql = "INSERT INTO articles (title, slug, content, excerpt, author_id, category_id, status, is_featured) 
                VALUES (:title, :slug, :content, :excerpt, :author_id, :category_id, :status, :is_featured)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'excerpt' => $data['excerpt'] ?? null,
            'author_id' => $data['author_id'] ?? 1,
            'category_id' => $data['category_id'] ?? 1,
            'status' => $data['status'] ?? 'published',
            'is_featured' => $data['is_featured'] ?? 0
        ]);
        
        return $this->db->lastInsertId();
        
    } catch (PDOException $e) {
        error_log("Erreur création article: " . $e->getMessage());
        return false;
    }
}

/**
 * Je modifie un article existant
 */
public function update($id, $data) {
    try {
        $sql = "UPDATE articles 
                SET title = :title, slug = :slug, content = :content, 
                    excerpt = :excerpt, category_id = :category_id, 
                    status = :status, is_featured = :is_featured,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'slug' => $data['slug'] ?? $this->generateSlug($data['title']),
            'content' => $data['content'],
            'excerpt' => $data['excerpt'] ?? null,
            'category_id' => $data['category_id'],
            'status' => $data['status'] ?? 'published',
            'is_featured' => $data['is_featured'] ?? 0
        ]);
        
    } catch (PDOException $e) {
        error_log("Erreur modification article: " . $e->getMessage());
        return false;
    }
}

/**
 * Je supprime un article de la base de données
 */
public function delete($id) {
    try {
        $sql = "DELETE FROM articles WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
        
    } catch (PDOException $e) {
        error_log("Erreur suppression article: " . $e->getMessage());
        return false;
    }
}

/**
 * Je génère un slug à partir d'un titre
 */
private function generateSlug($title) {
    return strtolower(preg_replace('/[^a-zA-Z0-9-]/', '-', 
           str_replace(['é','è','ê','à','â','ç'], ['e','e','e','a','a','c'], $title)));
}

}
