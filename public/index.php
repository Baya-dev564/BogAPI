<?php
// BlogAPI - Configuration base de données
// Développé par Baya AMELLAL PAYAN

class Database {
    // Je définis les paramètres de connexion MySQL
    private $host = 'localhost';
    private $db_name = 'blogapi_db';
    private $username = 'root';
    private $password = ''; // Je laisse vide car Laragon n'a pas de mot de passe par défaut
    private $port = '3308'; // Je utilise le port modifié dans Laragon pour éviter les conflits
    private $charset = 'utf8mb4'; // Je choisis utf8mb4 pour supporter les emojis et caractères spéciaux
    private $pdo = null; // Je stocke l'instance PDO pour éviter les reconnexions

    /**
     * Je crée la connexion à la base de données
     * @return PDO Instance de connexion PDO
     * @throws Exception Si la connexion échoue
     */
    public function connect() {
        // Je vérifie si la connexion existe déjà pour éviter les reconnexions
        if ($this->pdo === null) {
            try {
                // Je construis la chaîne DSN (Data Source Name) avec tous les paramètres
                $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset={$this->charset}";
                
                // Je configure les options PDO pour optimiser la sécurité et les performances
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Je active les exceptions pour les erreurs
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Je retourne des tableaux associatifs par défaut
                    PDO::ATTR_EMULATE_PREPARES => false, // Je désactive l'émulation pour utiliser les vraies requêtes préparées
                    PDO::ATTR_PERSISTENT => false // Je n'utilise pas les connexions persistantes
                ];
                
                // Je crée la connexion PDO avec gestion d'erreur
                $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
                
            } catch (PDOException $e) {
                // Je capture et je relance l'erreur avec un message personnalisé
                throw new Exception("Erreur de connexion à la base de données BlogAPI : " . $e->getMessage());
            }
        }
        
        // Je retourne l'instance PDO connectée
        return $this->pdo;
    }

    /**
     * Je ferme la connexion à la base de données
     */
    public function disconnect() {
        // Je libère la connexion PDO
        $this->pdo = null;
    }
}
