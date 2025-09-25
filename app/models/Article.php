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
}
