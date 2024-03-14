<?php
// Get form data
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

// Create an array with form data
$userData = array(
    'email' => $email,
    'username' => $username,
    'password' => $password
);

// Read existing JSON data from file
$filename = 'accounts.json';
$jsonData = file_get_contents($filename);
$accountsData = json_decode($jsonData, true);

// Add the new account data to the 'accounts' array
$accountsData['accounts'][] = $userData;

// Encode the updated array as JSON
$jsonData = json_encode($accountsData, JSON_PRETTY_PRINT);

// Write JSON data back to file
if(file_put_contents($filename, $jsonData . PHP_EOL)) {
    header('Location: account.html');
} else {
    echo "Unable to write data to $filename";
}
?>
