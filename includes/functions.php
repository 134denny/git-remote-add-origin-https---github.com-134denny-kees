<?php
// Ensure the database connection is included
include_once '../config/db.php';

// Function to register a new user
function registerUser($username, $password) {
    global $pdo;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        return $stmt->execute([$username, $hashedPassword]);
    } catch (Exception $e) {
        error_log('Error registering user: ' . $e->getMessage());
        return false;
    }
}

// Function to set a user account as inactive
function setUserInactive($userId) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE users SET active = 0 WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        error_log('Error setting user inactive: ' . $e->getMessage());
        return false;
    }
}

// Function to log in a user
function loginUser($username, $password) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id, password, active FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['active'] == 0) {
                // Account is inactive
                return false;
            }

            // Password is correct and account is active
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            return true;
        }
        return false;
    } catch (Exception $e) {
        error_log('Error logging in user: ' . $e->getMessage());
        return false;
    }
}

// Function to check if a user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getLoggedInUserId() {
    return $_SESSION['user_id'] ?? null;
}

function isUserAdmin() {
    global $pdo;
    if (isUserLoggedIn()) {
        try {
            $stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            return $user['is_admin'] == 1;
        } catch (Exception $e) {
            error_log('Error checking admin status: ' . $e->getMessage());
            return false;
        }
    }
    return false;
}

function isUserSuperAdmin() {
    global $pdo;
    if (isUserLoggedIn()) {
        try {
            $stmt = $pdo->prepare("SELECT is_super_admin FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            return $user['is_super_admin'] == 1;
        } catch (Exception $e) {
            error_log('Error checking super admin status: ' . $e->getMessage());
            return false;
        }
    }
    return false;
}

// Function to log out a user
function logoutUser() {
    session_start();
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
