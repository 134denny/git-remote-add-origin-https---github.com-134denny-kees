<?php
// Include database connection and helper functions
include_once '../config/db.php';
include_once '../includes/functions.php';

if (!isUserSuperAdmin()) {
    // Redirect to the home page or an error page
    header("Location: /error2.php");
    exit();
}

// Fetch all users
try {
    $stmt = $pdo->prepare("SELECT id, username, email, is_admin, is_super_admin FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll();
} catch (Exception $e) {
    error_log('Error fetching user list: ' . $e->getMessage());
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <!-- Add any additional CSS here -->
    <style>
        /* Basic styling for the user list table */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Super Admin Dashboard</h1>
    <h2>All Users</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Admin</th>
                <th>Super Admin</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
                        <td><?php echo $user['is_super_admin'] ? 'Yes' : 'No'; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
