<?php
// BlogAPI - Contrôleur des utilisateurs
// Développé par Baya AMELLAL PAYAN

class UserController {
    private $db;
    private $user;

    public function __construct($database) {
        // Je récupère la connexion et j'initialise le modèle
        $this->db = $database;
        $this->user = new User($this->db);
    }

    /**
     * Je récupère tous les utilisateurs
     */
    public function getAll() {
        try {
            // Je récupère tous les utilisateurs actifs
            $users = $this->user->getAll();

            // Je retourne la réponse JSON
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'data' => $users,
                'count' => count($users)
            ]);

        } catch (Exception $e) {
            // Je gère les erreurs
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la récupération des utilisateurs',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Je récupère un utilisateur par son ID
     */
    public function getById($id) {
        try {
            // Je valide l'ID
            if (!is_numeric($id) || $id <= 0) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID utilisateur invalide'
                ]);
                return;
            }

            // Je récupère l'utilisateur
            $user = $this->user->getById($id);

            if ($user) {
                // Je retourne l'utilisateur
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'data' => $user
                ]);
            } else {
                // Utilisateur non trouvé
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Utilisateur non trouvé'
                ]);
            }

        } catch (Exception $e) {
            // Je gère les erreurs
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'utilisateur',
                'error' => $e->getMessage()
            ]);
        }
    }
}
