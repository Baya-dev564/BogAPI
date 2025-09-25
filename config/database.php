<?php
// BlogAPI - Configuration base de données
// Développé par Baya AMELLAL PAYAN

class Database {
    // Je définis les paramètres de connexion
    private $host = 'localhost';
    private $db_name = 'blogapi_db';
    private $username = 'root';
    private $password = ''; // Je remplace par mon vrai mot de passe
    private $port = '3308'; // Je utilise le port configuré dans Laragon
    private $charset = 'utf8mb4';
    private $pdo;

    public function connect() {
        // Je vérifie si la connexion existe déjà
        if ($this->pdo === null) {
            try {
                // Je construis la chaîne DSN avec le port
                $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset={$this->charset}";
                
                // Je configure les options PDO pour la sécurité
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ];
                
                // Je crée la connexion PDO
                $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
                
            } catch (PDOException $e) {
                // Je gère les erreurs de connexion
                throw new Exception("Erreur de connexion à la base : " . $e->getMessage());
            }
        }
        
        // Je retourne la connexion
        return $this->pdo;
    }
}
