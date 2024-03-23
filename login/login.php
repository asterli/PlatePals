<?php
session_start();

// Handle login attempt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userInput = $_POST['username']; // This could be either a username or an email
    $password = $_POST['password'];

    $databaseFile = '../recipes.db';
    try {
        $pdo = new PDO('sqlite:' . $databaseFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :userInput OR email = :userInput");
        $stmt->execute([':userInput' => $userInput]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($account && password_verify($password, $account['password'])) {
            $_SESSION['user_id'] = $account['id'];
            header('Location: ../account/account.html');
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid username/email or password. Please try again.";
        }
    } catch (PDOException $e) {
        $_SESSION['login_error'] = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="../base.css" />
    <link rel="stylesheet" type="text/css" href="login.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Rufina:wght@700&display=swap" rel="stylesheet">
    <title>PlatePals - Login</title>
</head>

<body>
    <nav class="rufina">
        <ul>
            <li id="logo">
                <a href="../home.php"><img width="60px" src="../images/logo.png" /></a>
            </li>
            <div id="end-nav">
                <li><a class="active" href="login.php">Login</a></li>
                <li><a href="../signup/signup.php">Sign Up</a></li>
            </div>
        </ul>
    </nav>
    <main>
        <div id="sign-in-text">
            <h1>Sign in here:</h1>
            <h3>Have all your favorite recipes in one place!</h3>
        </div>

        <form id="login-form" action="login.php" method="post">
            <input type="text" id="username" placeholder="Username or Email" name="username" required />
            <input type="password" id="password" placeholder="Password" name="password" required />
            <input id="submit" type="submit" value="Login" />

            <?php if (isset($_SESSION['login_error'])) : ?>
                <div class="error-message" style="color:red; margin-top:10px;">
                    <?php
                    echo $_SESSION['login_error'];
                    unset($_SESSION['login_error']);
                    ?>
                </div>
            <?php endif; ?>
        </form>
        <div class="centered-line"></div>
        <div id="create-account-text">
            <h3>New Here?</h3>
            <h4>Create an account! It's quick and easy!</h4>
        </div>
        <input id="create" type="submit" value="Create an account!" onclick="location.href='../signup/signup.html';" />
    </main>
    <footer class="lato">
        <p>&copy; 2024 PlatePals Inc. All rights Reserved</p>
    </footer>
</body>

</html>