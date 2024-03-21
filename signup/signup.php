<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($username) || empty($password)) {
        $_SESSION['signup_error'] = 'All fields are required.';
        header('Location: signup.html'); 
        exit();
    }

    try {
        $pdo = new PDO('sqlite:../recipes.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $_SESSION['signup_error'] = 'Username or email already exists.';
            header('Location: ../account/account.html');
            exit();
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
        $stmt->execute([':username' => $username, ':password' => $hashedPassword, ':email' => $email]);

        $_SESSION['user'] = $username;
        $_SESSION['user_id'] = $pdo->lastInsertId(); 
        $_SESSION['signup_success'] = "Account successfully created. Welcome, $username!";
        header('Location: ../account/account.html'); 
    } catch (PDOException $e) {
        $_SESSION['signup_error'] = "An error occurred while creating your account. Please try again later.";
        header('Location: signup.html');
    }
} else {
    header('Location: signup.html');
}
