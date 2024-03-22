<?php

session_start();
header('Content-Type: application/json');

$databaseFile = '../recipes.db';
$recipeId = $_GET['id'] ?? '';

try {
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = :id");
    $stmt->execute([':id' => $recipeId]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($recipe) {
        $recipe['ingredients'] = json_decode($recipe['ingredients'], true);
        $recipe['instructions'] = json_decode($recipe['instructions'], true);
        $recipe['comments'] = json_decode($recipe['comments'], true);
        echo json_encode(['success' => true, 'recipe' => $recipe]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Recipe not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
