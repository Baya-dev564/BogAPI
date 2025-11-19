<?php
// BlogAPI - Modèle catégorie
// Développé par Baya AMELLAL PAYAN

class Category {
    // Je définis la table et les propriétés
    private $table = 'categories';
    private $db;
    
    public $id;
    public $name;
    public $slug;
    public $description;
    public $color;
    public $icon;
    public $is_active;
    public $created_at;
    public $updated_at;

    public function __construct($database) {
        // Je récupère la connexion à la base
        $this->db = $database->connect();
    }

    /**
     * Je récupère toutes les catégories actives
     */
    public function getAll() {
        $query = "SELECT * FROM {$this->table} 
                  WHERE is_active = 1 
                  ORDER BY name ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Je récupère une catégorie par son slug
     */
    public function getBySlug($slug) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE slug = :slug AND is_active = 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    /**
     * Je récupère les catégories avec le nombre d'articles
     */
    public function getWithArticleCount() {
        $query = "SELECT c.*, COUNT(a.id) as article_count
                  FROM {$this->table} c
                  LEFT JOIN articles a ON c.id = a.category_id AND a.status = 'published'
                  WHERE c.is_active = 1
                  GROUP BY c.id
                  ORDER BY c.name ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
