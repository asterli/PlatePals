<?php

session_start(); // Ensure session start at the beginning to access session variables
header('Content-Type: application/json');

$databaseFile = '../recipes.db';

if (!isset($_SESSION['user_id'])) {
    // If there's no user_id in the session, return an error or empty array
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

try {
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch recipes specific to the logged-in user by using their user_id from the session
    $query = "SELECT * FROM recipes WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':user_id' => $_SESSION['user_id']]);

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
