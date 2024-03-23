<?php
$databaseFile = '../recipes.db';

try {
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

    $query = "SELECT * FROM recipes";

    if ($searchQuery !== '') {
        $query .= " WHERE title LIKE :searchQuery";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':searchQuery', '%' . $searchQuery . '%', PDO::PARAM_STR);
    } else {
        $stmt = $pdo->query($query);
    }
    
    $stmt->execute();
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($recipes);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
