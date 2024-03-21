<?php

$databaseFile = 'recipes.db';

try {
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $createQuery = "CREATE TABLE IF NOT EXISTS recipes (
        id TEXT PRIMARY KEY,
        title TEXT NOT NULL,
        author TEXT NOT NULL,
        description TEXT NOT NULL,
        ingredients TEXT NOT NULL, 
        instructions TEXT NOT NULL, 
        image TEXT,
        category TEXT,
        comments TEXT NOT NULL 
    )";
    $pdo->exec($createQuery);

    echo "Database and recipes table created successfully.\n";

    $jsonFilePath = 'recipes.json';

    if (file_exists($jsonFilePath)) {
        $jsonContent = file_get_contents($jsonFilePath);
        $data = json_decode($jsonContent, true);

        $insertQuery = "INSERT INTO recipes (id, title, author, description, ingredients, instructions, image, category, comments) 
                        VALUES (:id, :title, :author, :description, :ingredients, :instructions, :image, :category, :comments)";
        $stmt = $pdo->prepare($insertQuery);

        foreach ($data['recipes'] as $recipe) {
            $ingredients = json_encode($recipe['ingredients']);
            $instructions = json_encode($recipe['instructions']);
            $comments = json_encode($recipe['comments']);

            $stmt->execute([
                ':id' => $recipe['id'],
                ':title' => $recipe['title'],
                ':author' => $recipe['author'],
                ':description' => $recipe['description'],
                ':ingredients' => $ingredients,
                ':instructions' => $instructions,
                ':image' => $recipe['image'],
                ':category' => $recipe['category'],
                ':comments' => $comments
            ]);

            echo "Recipe '{$recipe['title']}' inserted successfully.\n";
        }
    } else {
        echo "JSON file does not exist.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit;
}
