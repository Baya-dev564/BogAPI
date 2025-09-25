<?php
// BlogAPI - Modèle utilisateur
// Développé par Baya AMELLAL PAYAN

class User {
    // Je définis la table et les champs
    private $table = 'users';
    private $db;
    
    // Je définis les propriétés de l'utilisateur
    public $id;
    public $username;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $role;
    public $avatar;
    public $bio;
    public $is_active;
    public $created_at;
    public $updated_at;

    public function __construct($database) {
        // Je récupère la connexion à la base de données
        $this->db = $database->connect();
    }

    /**
     * Je récupère tous les utilisateurs actifs
     */
    public function getAll() {
        // Je prépare ma requête pour récupérer les utilisateurs actifs
        $query = "SELECT id, username, email, first_name, last_name, role, avatar, bio, created_at 
                  FROM {$this->table} 
                  WHERE is_active = 1 
                  ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Je récupère un utilisateur par son ID
     */
    public function getById($id) {
        // Je prépare ma requête avec l'ID spécifique
        $query = "SELECT id, username, email, first_name, last_name, role, avatar, bio, created_at 
                  FROM {$this->table} 
                  WHERE id = :id AND is_active = 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
}
