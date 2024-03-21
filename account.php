<?php

header('Content-Type: application/json');

$databaseFile = 'recipes.db';

try {
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM recipes";
    $stmt = $pdo->query($query);

    $recipes = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Decode the JSON text back to arrays for ingredients, instructions, and comments
        $row['ingredients'] = json_decode($row['ingredients'], true);
        $row['instructions'] = json_decode($row['instructions'], true);
        $row['comments'] = json_decode($row['comments'], true);
        $recipes[] = $row;
    }

    echo json_encode(['recipes' => $recipes]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
    exit;
}
