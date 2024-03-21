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

    $stmt = $pdo->prepare("SELECT comments FROM recipes WHERE id = :id");
    $stmt->execute([':id' => $recipeId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $currentComments = json_decode($result['comments'], true);
        $currentComments[] = ['name' => $name, 'comment' => $commentText];
        $updatedComments = json_encode($currentComments);
        $updateStmt = $pdo->prepare("UPDATE recipes SET comments = :comments WHERE id = :id");
        $updateStmt->execute([':comments' => $updatedComments, ':id' => $recipeId]);

        echo json_encode(['success' => true, 'comment' => ['name' => $name, 'text' => $commentText]]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Recipe not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
