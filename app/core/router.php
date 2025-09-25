<?php
// BlogAPI - Système de routing REST complet
// Développé par Baya AMELLAL PAYAN

class Router {
    private $routes = [];
    private $db;

    public function __construct($database) {
        // Je récupère la connexion à la base
        $this->db = $database;
    }

    /**
     * Je ajoute une route GET
     */
    public function get($path, $controller, $method) {
        $this->addRoute('GET', $path, $controller, $method);
    }

    /**
     * Je ajoute une route POST
     */
    public function post($path, $controller, $method) {
        $this->addRoute('POST', $path, $controller, $method);
    }

    /**
     * Je ajoute une route à la liste
     */
    private function addRoute($httpMethod, $path, $controller, $method) {
        $this->routes[] = [
            'method' => $httpMethod,
            'path' => $path,
            'controller' => $controller,
            'action' => $method
        ];
    }

    /**
     * Je traite la requête et trouve la route correspondante
     */
    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestPath = $this->getPath();

        // Je parcours toutes les routes
        foreach ($this->routes as $route) {
            if ($this->matchRoute($route, $requestMethod, $requestPath)) {
                $this->executeRoute($route, $requestPath);
                return;
            }
        }

        // Route non trouvée
        $this->notFound();
    }

    /**
     * Je récupère le chemin de la requête
     */
    private function getPath() {
        $path = $_SERVER['REQUEST_URI'];
        
        // Je retire les paramètres GET
        if (($pos = strpos($path, '?')) !== false) {
            $path = substr($path, 0, $pos);
        }
        
        // Je retire le dossier public si présent
        $path = str_replace('/public', '', $path);
        
        return $path === '' ? '/' : $path;
    }

    /**
     * Je vérifie si la route correspond à la requête
     */
    private function matchRoute($route, $method, $path) {
        // Je vérifie la méthode HTTP
        if ($route['method'] !== $method) {
            return false;
        }

        // Je traite les paramètres dynamiques comme {slug} ou {id}
        $routePath = $route['path'];
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        return preg_match($pattern, $path);
    }

    /**
     * Je exécute la route trouvée
     */
    private function executeRoute($route, $path) {
        try {
            // Je récupère les paramètres de l'URL
            $params = $this->extractParams($route['path'], $path);
            
            // Je crée le contrôleur
            $controllerName = $route['controller'];
            $controller = new $controllerName($this->db);
            
            // Je exécute la méthode
            $method = $route['action'];
            
            if (empty($params)) {
                $controller->$method();
            } else {
                $controller->$method(...$params);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Je extrait les paramètres de l'URL
     */
    private function extractParams($routePath, $actualPath) {
        $routeParts = explode('/', trim($routePath, '/'));
        $actualParts = explode('/', trim($actualPath, '/'));
        
        $params = [];
        
        for ($i = 0; $i < count($routeParts); $i++) {
            if (preg_match('/\{([^}]+)\}/', $routeParts[$i])) {
                $params[] = $actualParts[$i] ?? '';
            }
        }
        
        return $params;
    }

    /**
     * Je gère les routes non trouvées
     */
    private function notFound() {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Route non trouvée',
            'requested_path' => $this->getPath(),
            'method' => $_SERVER['REQUEST_METHOD']
        ]);
    }
}
?>
