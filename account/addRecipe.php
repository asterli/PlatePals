<?php

session_start();

header('Content-Type: application/json');

$databaseFile = '../recipes.db';
$targetDir = "../images/recipes/";

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User is not logged in.');
    }

    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the currently logged-in user's name
    $userStmt = $pdo->prepare("SELECT username FROM users WHERE id = :user_id");
    $userStmt->execute([':user_id' => $_SESSION['user_id']]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('User not found.');
    }

    $username = $user['username'];

    if (!isset($_FILES['recipeImage']['error']) || $_FILES['recipeImage']['error'] != 0 || !is_uploaded_file($_FILES['recipeImage']['tmp_name'])) {
        throw new Exception('An image is required and must be uploaded successfully.');
    }

    $originalName = $_FILES['recipeImage']['name'];
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8));
    $targetFilePath = $targetDir . $basename . "." . $extension;

    if (!move_uploaded_file($_FILES['recipeImage']['tmp_name'], $targetFilePath)) {
        throw new Exception('Failed to move uploaded file.');
    }

    $insertQuery = "INSERT INTO recipes (user_id, title, author, description, ingredients, instructions, image, category) 
                    VALUES (:user_id, :title, :author, :description, :ingredients, :instructions, :image, :category)";

    $stmt = $pdo->prepare($insertQuery);
    $ingredients = json_encode(explode("\n", $_POST['recipeIngredients']));
    $instructions = json_encode(explode("\n", $_POST['recipeInstructions']));

    $stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':title' => $_POST['recipeTitle'],
        ':author' => $username,
        ':description' => $_POST['recipeDescription'],
        ':ingredients' => $ingredients,
        ':instructions' => $instructions,
        ':image' => $targetFilePath,
        ':category' => $_POST['recipeCategory'],
    ]);

    $lastId = $pdo->lastInsertId();
    echo json_encode(['success' => true, 'message' => 'Recipe added successfully.', 'id' => $lastId]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
