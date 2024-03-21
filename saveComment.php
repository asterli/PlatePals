<?php

header('Content-Type: application/json');

$databaseFile = 'recipes.db';
$name = $_POST['name'] ?? '';
$commentText = $_POST['comment'] ?? '';
$recipeId = $_POST['recipeId'] ?? '';

if (empty($name) || empty($commentText) || empty($recipeId)) {
    echo json_encode(['success' => false, 'message' => 'Missing fields']);
    exit;
}

try {
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO comments (recipe_id, name, text) VALUES (:recipe_id, :name, :text)");
    $stmt->execute([
        ':recipe_id' => $recipeId,
        ':name' => $name,
        ':text' => $commentText,
    ]);

    echo json_encode(['success' => true, 'comment' => ['name' => $name, 'text' => $commentText]]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
