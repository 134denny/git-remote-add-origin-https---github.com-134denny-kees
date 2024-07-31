<?php
session_start();
include_once '../config/db.php';
include_once '../includes/functions.php';

if (!isUserLoggedIn()) {
    header("Location: index.php");
    exit();
}

// Handle CRUD operations for cars
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        // Create car
        $km_stand = $_POST['km_stand'];
        $name = $_POST['name'];
        $number = $_POST['number'];
        $last_used = $_POST['last_used'];

        $stmt = $pdo->prepare("INSERT INTO cars (km_stand, name, number, last_used, is_active) VALUES (?, ?, ?, ?, 0)");
        $stmt->execute([$km_stand, $name, $number, $last_used]);

    } elseif (isset($_POST['update'])) {
        // Update car
        $id = $_POST['id'];
        $km_stand = $_POST['km_stand'];
        $name = $_POST['name'];
        $number = $_POST['number'];
        $last_used = $_POST['last_used'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        $stmt = $pdo->prepare("UPDATE cars SET km_stand = ?, name = ?, number = ?, last_used = ?, is_active = ? WHERE id = ?");
        $stmt->execute([$km_stand, $name, $number, $last_used, $is_active, $id]);

    } elseif (isset($_POST['delete'])) {
        // Delete car
        $id = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
        $stmt->execute([$id]);

    } elseif (isset($_POST['activate'])) {
        // Activate cars
        $ids = $_POST['car_ids'];
        $ids = implode(',', array_map('intval', $ids)); // Convert to comma-separated list
        $stmt = $pdo->prepare("UPDATE cars SET is_active = 1 WHERE id IN ($ids)");
        $stmt->execute();

    } elseif (isset($_POST['add_repair'])) {
        // Add repair
        $car_id = $_POST['car_id'];
        $repair_description = $_POST['repair_description'];
        $repair_date = $_POST['repair_date'];

        $stmt = $pdo->prepare("INSERT INTO repairs (car_id, repair_description, repair_date) VALUES (?, ?, ?)");
        $stmt->execute([$car_id, $repair_description, $repair_date]);

    } elseif (isset($_POST['delete_repair'])) {
        // Delete repair
        $id = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM repairs WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Fetch only active cars
$stmt = $pdo->query("SELECT * FROM cars WHERE is_active = 1");
$cars = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <h1>Car Dashboard</h1>

    <!-- Button to open the activate cars modal -->
    <button type="button" data-toggle="modal" data-target="#activateCarsModal">Activate Cars</button>

    <!-- Car List -->
    <table>
        <thead>
            <tr>
                <th>KM Stand</th>
                <th>Name</th>
                <th>Number</th>
                <th>Last Used</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cars as $car): ?>
            <tr>
                <td><?= htmlspecialchars($car['km_stand']) ?></td>
                <td><?= htmlspecialchars($car['name']) ?></td>
                <td><?= htmlspecialchars($car['number']) ?></td>
                <td><?= htmlspecialchars($car['last_used']) ?></td>
                <td>
                    <!-- Button to open the edit car modal -->
                    <button type="button" data-toggle="modal" data-target="#editCarModal" data-id="<?= $car['id'] ?>" data-km-stand="<?= $car['km_stand'] ?>" data-name="<?= htmlspecialchars($car['name']) ?>" data-number="<?= htmlspecialchars($car['number']) ?>" data-last-used="<?= $car['last_used'] ?>">Edit</button>
                    <!-- Button to open the repair modal -->
                    <button type="button" data-toggle="modal" data-target="#repairModal" data-car-id="<?= $car['id'] ?>" data-car-name="<?= htmlspecialchars($car['name']) ?>">View Repairs</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Edit Car Modal -->
    <div id="editCarModal" class="modal">
        <div class="modal-content">
            <span class="close" data-dismiss="modal">&times;</span>
            <h2>Edit Car</h2>
            <form method="POST" action="dashboard.php">
                <input type="hidden" name="update" value="1">
                <input type="hidden" id="edit_id" name="id">
                <label for="edit_km_stand">KM Stand:</label>
                <input type="number" id="edit_km_stand" name="km_stand" required>
                
                <label for="edit_name">Name:</label>
                <input type="text" id="edit_name" name="name" required>
                
                <label for="edit_number">Number:</label>
                <input type="text" id="edit_number" name="number" required>
                
                <label for="edit_last_used">Last Used:</label>
                <input type="date" id="edit_last_used" name="last_used" required>
                
                <label for="edit_is_active">Active:</label>
                <input type="checkbox" id="edit_is_active" name="is_active">
                
                <button type="submit">Update Car</button>
            </form>
        </div>
    </div>

    <!-- Repair Management Modal -->
    <div id="repairModal" class="modal">
        <div class="modal-content">
            <span class="close" data-dismiss="modal">&times;</span>
            <h2>Repairs for <span id="carName"></span></h2>
            <form method="POST" action="dashboard.php" id="repairForm">
                <input type="hidden" name="add_repair" value="1">
                <input type="hidden" id="car_id" name="car_id">
                <label for="repair_description">Repair Description:</label>
                <textarea id="repair_description" name="repair_description" required></textarea>
                
                <label for="repair_date">Repair Date:</label>
                <input type="date" id="repair_date" name="repair_date" required>
                
                <button type="submit">Add Repair</button>
            </form>

            <!-- Repairs List -->
            <table id="repairsList">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="repairTableBody">
                    <!-- Repairs will be loaded here via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Activate Cars Modal -->
    <div id="activateCarsModal" class="modal">
        <div class="modal-content">
            <span class="close" data-dismiss="modal">&times;</span>
            <h2>Activate Cars</h2>
            <form method="POST" action="dashboard.php">
                <input type="hidden" name="activate" value="1">
                <label>Select cars to activate:</label>
                <select multiple name="car_ids[]">
                    <?php
                    // Fetch inactive cars for activation
                    $stmt = $pdo->query("SELECT id, name FROM cars WHERE is_active = 0");
                    $inactiveCars = $stmt->fetchAll();
                    foreach ($inactiveCars as $car): ?>
                        <option value="<?= $car['id'] ?>"><?= htmlspecialchars($car['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Activate Selected Cars</button>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
