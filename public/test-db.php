<?php
echo "Test DB - Développé par Baya AMELLAL PAYAN<br>";

try {
    require_once '../config/Database.php';
    
    $database = new Database();
    $conn = $database->getConnection();
    
    echo " Connexion DB OK<br>";
    
    $stmt = $conn->query("SELECT * FROM articles");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Articles trouvés:</h3>";
    foreach ($articles as $article) {
        echo "- " . $article['title'] . " par " . $article['created_at'] . "<br>";
    }
    
    echo "<hr><h3>JSON:</h3>";
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'count' => count($articles), 'data' => $articles]);
    
} catch (Exception $e) {
    echo " Erreur: " . $e->getMessage();
}
?>
