<?php
$databaseFile = '../recipes.db';

try {
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM recipes";
    $stmt = $pdo->query($query);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($recipes);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
