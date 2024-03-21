<?php

header('Content-Type: application/json');

$databaseFile = 'recipes.db';
$targetDir = "images/";

try {
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

    $insertQuery = "INSERT INTO recipes (id, title, author, description, ingredients, instructions, image, category, comments) 
                    VALUES (:id, :title, :author, :description, :ingredients, :instructions, :image, :category, '[]')";

    $stmt = $pdo->prepare($insertQuery);
    $id = uniqid('recipe_', true);
    $ingredients = json_encode(explode("\n", $_POST['recipeIngredients']));
    $instructions = json_encode(explode("\n", $_POST['recipeInstructions']));

    $stmt->execute([
        ':id' => $id,
        ':title' => $_POST['recipeTitle'],
        ':author' => 'Anonymous', 
        ':description' => $_POST['recipeDescription'],
        ':ingredients' => $ingredients,
        ':instructions' => $instructions,
        ':image' => $targetFilePath, 
        ':category' => $_POST['recipeTag'] 
    ]);

    echo json_encode(['success' => true, 'message' => 'Recipe added successfully.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
