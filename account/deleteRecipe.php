<?php
session_start();
header('Content-Type: application/json');

$databaseFile = '../recipes.db';
$recipeId = $_POST['recipeId'] ?? '';

if (!isset($_SESSION['user_id']) || empty($recipeId)) {
    echo json_encode(["success" => false, "message" => "Unauthorized or invalid request"]);
    exit;
}

try {
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Delete recipe from the database
    $stmt = $pdo->prepare("DELETE FROM recipes WHERE id = :id AND user_id = :user_id");
    $stmt->execute([':id' => $recipeId, ':user_id' => $_SESSION['user_id']]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Recipe deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Recipe not found or you do not have permission to delete it"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
