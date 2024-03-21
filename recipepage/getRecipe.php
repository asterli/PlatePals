<?php

session_start(); // Start the session to access user information
header('Content-Type: application/json');

$databaseFile = '../recipes.db';
$recipeId = $_GET['id'] ?? '';
$userId = $_SESSION['user_id'] ?? null; // Assuming you store user_id in the session upon login

try {
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = :id AND user_id = :user_id");
    $stmt->execute([':id' => $recipeId, ':user_id' => $userId]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($recipe) {
        $recipe['ingredients'] = json_decode($recipe['ingredients'], true);
        $recipe['instructions'] = json_decode($recipe['instructions'], true);
        $recipe['comments'] = json_decode($recipe['comments'], true);
        echo json_encode(['success' => true, 'recipe' => $recipe]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Recipe not found or you do not have permission to view it']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
