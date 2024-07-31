<?php
include_once '../config/db.php';
include_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (registerUser($username, $password)) {
        // Optionally set user as active immediately after registration
        // You can set additional logic for user activation
        echo "Registration successful.";
    } else {
        echo "Registration failed.";
    }
}
?>

<form method="POST" action="register.php">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">Register</button>
</form>

