<?php

$databaseFile = 'recipes.db';

try {
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $createUsersTableQuery = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL
    )";
    $pdo->exec($createUsersTableQuery);
    echo "Users table created successfully.\n";

    $createRecipesTableQuery = "CREATE TABLE IF NOT EXISTS recipes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        title TEXT NOT NULL,
        author TEXT NOT NULL,
        description TEXT NOT NULL,
        ingredients TEXT NOT NULL, 
        instructions TEXT NOT NULL, 
        image TEXT NOT NULL,
        category TEXT NOT NULL,
        comments TEXT NOT NULL DEFAULT '[]',
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";
    $pdo->exec($createRecipesTableQuery);
    echo "Recipes table created successfully.\n";

    $jsonFilePath = 'account/recipes.json';
    if (file_exists($jsonFilePath)) {
        $jsonContent = file_get_contents($jsonFilePath);
        $data = json_decode($jsonContent, true);

        $insertRecipeQuery = "INSERT INTO recipes (user_id, title, author, description, ingredients, instructions, image, category, comments) 
                              VALUES (:user_id, :title, :author, :description, :ingredients, :instructions, :image, :category, :comments)";
        $stmt = $pdo->prepare($insertRecipeQuery);

        $userId = 1;

        foreach ($data['recipes'] as $recipe) {
            $ingredients = json_encode($recipe['ingredients']);
            $instructions = json_encode($recipe['instructions']);
            $category = json_encode($recipe['category']);
            $comments = json_encode($recipe['comments']);

            $stmt->execute([
                ':user_id' => $userId,
                ':title' => $recipe['title'],
                ':author' => $recipe['author'],
                ':description' => $recipe['description'],
                ':ingredients' => $ingredients,
                ':instructions' => $instructions,
                ':image' => $recipe['image'],
                ':category' => $category,
                ':comments' => $comments
            ]);

            echo "Recipe '{$recipe['title']}' inserted successfully for user ID {$userId}.\n";
        }
    } else {
        echo "JSON file does not exist.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit;
}
