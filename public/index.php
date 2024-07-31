<?php
session_start();
include_once '../config/db.php';
include_once '../includes/error_handler.php';
include_once '../includes/exception_handler.php';
include_once '../includes/shutdown_function.php';
include_once '../includes/functions.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (loginUser($username, $password)) {
        header("Location: dashboard.php");
    } else {
        echo "Login failed. Invalid username or password.";
    }
}
?>

<form method="POST" action="index.php">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">Login</button>
</form>
