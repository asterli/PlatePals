<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['signup_error'] = 'Invalid email format.';
        header('Location: signup.php');
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['signup_error'] = 'Passwords do not match.';
        header('Location: signup.php');
        exit();
    }

    try {
        $pdo = new PDO('sqlite:../recipes.db'); // Or your actual database connection setup
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $_SESSION['signup_error'] = 'Username or email already exists.';
            header('Location: signup.php');
            exit();
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
        $stmt->execute([':username' => $username, ':password' => $hashedPassword, ':email' => $email]);

        $_SESSION['user'] = $username;
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['signup_success'] = "Account successfully created. Welcome, $username!";
        header('Location: ../account/account.html'); // Adjust if necessary
        exit();
    } catch (PDOException $e) {
        $_SESSION['signup_error'] = "An error occurred while creating your account. Please try again later.";
        header('Location: signup.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="../base.css" />
    <link rel="stylesheet" type="text/css" href="signup.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Rufina:wght@700&display=swap" rel="stylesheet">
    <title>Sign Up</title>
</head>

<body>
    <nav class="rufina">
        <ul>
            <li id="logo">
                <a href="../home.php"><img width="60px" src="../images/logo.png" /></a>
            </li>
            <div id="end-nav">
                <li><a href="../login/login.php">Login</a></li>
                <li><a class="active" href="signup.php">Sign Up</a></li>
            </div>
        </ul>
    </nav>
    <main>
        <div id="create-text">
            <h1>Create an account!</h1>
            <h3>Stay up to date with the latest recipes!</h3>
            <h4>Don’t miss out! Sign up now and get all the new recipes first.</h4>
        </div>
        <form id="signup-form" action="signup.php" method="post">
            <input type="text" id="email" placeholder="Email" name="email" required />
            <input type="text" id="username" placeholder="Create Username" name="username" required />
            <input type="password" id="password" placeholder="Create Password" name="password" required />
            <input type="password" id="confirmPassword" placeholder="Confirm Password" name="confirm_password" required />
            <?php if (isset($_SESSION['signup_error'])) : ?>
                <div style="margin-bottom: 20px; color:red;"><?php echo $_SESSION['signup_error'];
                                                                unset($_SESSION['signup_error']); ?></div>
            <?php endif; ?>
            <input id="submit" type="submit" value="Create an Account" />
        </form>
    </main>
    <footer>
        <p>&copy; 2024 PlatePals Inc. All rights Reserved</p>
    </footer>
</body>

</html>