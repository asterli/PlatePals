<?php
// Get form data
$email= $_POST['email'];
$username= $_POST['username'];
$password = $_POST['password'];
echo($email);
echo($username);
echo($password);
// Read JSON data from file
$filename = 'accounts.json';
$jsonData = file_get_contents($filename);
$accounts = json_decode($jsonData, true);

// Check if credentials match any account
$loggedIn = false;
foreach ($accounts['accounts'] as $account) {
    if (($account['email'] === $email || $account['username'] === $username) && $account['password'] === $password) {
        $loggedIn = true;
        break;
    }
}

// If credentials match, redirect to success page
if ($loggedIn) {
    header('Location: account.html');
    exit();
} else {
    // If credentials don't match, display error message
    echo "Invalid email/username or password. Please try again.";
}
?>
