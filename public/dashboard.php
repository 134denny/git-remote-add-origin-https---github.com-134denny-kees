<?php
session_start();
include_once '../config/db.php';
include_once '../includes/functions.php';

if (!isUserLoggedIn()) {
    header("Location: index.php");
    exit();
}

$user_id = getLoggedInUserId();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $km_stand = $_POST['km_stand'];
        $name = $_POST['name'];
        $number = $_POST['number'];
        $last_used = $_POST['last_used'];

        $stmt = $pdo->prepare("INSERT INTO cars (km_stand, name, number, last_used, is_active, user_id) VALUES (?, ?, ?, ?, 0, ?)");
        $stmt->execute([$km_stand, $name, $number, $last_used, $user_id]);

    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $km_stand = $_POST['km_stand'];
        $name = $_POST['name'];
        $number = $_POST['number'];
        $last_used = $_POST['last_used'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        $stmt = $pdo->prepare("UPDATE cars SET km_stand = ?, name = ?, number = ?, last_used = ?, is_active = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$km_stand, $name, $number, $last_used, $is_active, $id, $user_id]);

    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);

    } elseif (isset($_POST['activate'])) {
        $ids = $_POST['car_ids'];
        if (is_array($ids)) {
            $ids = implode(',', array_map('intval', $ids));
            $stmt = $pdo->prepare("UPDATE cars SET is_active = 1 WHERE id IN ($ids) AND user_id = ?");
            $stmt->execute([$user_id]);
        }

    } elseif (isset($_POST['add_repair'])) {
        $car_id = $_POST['car_id'];
        $repair_description = $_POST['repair_description'];
        $repair_date = $_POST['repair_date'];

        $stmt = $pdo->prepare("INSERT INTO repairs (car_id, repair_description, repair_date) VALUES (?, ?, ?)");
        $stmt->execute([$car_id, $repair_description, $repair_date]);

    } elseif (isset($_POST['delete_repair'])) {
        $id = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM repairs WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Fetch active cars
$stmt = $pdo->prepare("SELECT * FROM cars WHERE is_active = 1 AND user_id = ?");
$stmt->execute([$user_id]);
$cars = $stmt->fetchAll();

// Fetch all cars for activation modal
$stmt = $pdo->prepare("SELECT * FROM cars WHERE user_id = ?");
$stmt->execute([$user_id]);
$allCars = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <h1>Car Dashboard</h1>

        <!-- Button to open the activate cars modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#activateCarsModal">Activate Cars</button>

        <!-- Car List -->
        <table class="table mt-4">
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
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editCarModal"
                            data-id="<?= $car['id'] ?>"
                            data-km-stand="<?= $car['km_stand'] ?>"
                            data-name="<?= htmlspecialchars($car['name']) ?>"
                            data-number="<?= htmlspecialchars($car['number']) ?>"
                            data-last-used="<?= $car['last_used'] ?>"
                            data-is-active="<?= $car['is_active'] ?>">Edit</button>

                        <!-- Button to open the repair modal -->
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#repairModal"
                            data-car-id="<?= $car['id'] ?>"
                            data-car-name="<?= htmlspecialchars($car['name']) ?>">View Repairs</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Car Modal -->
    <div class="modal fade" id="editCarModal" tabindex="-1" role="dialog" aria-labelledby="editCarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCarModalLabel">Edit Car</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="dashboard.php">
                    <div class="modal-body">
                        <input type="hidden" name="update" value="1">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group">
                            <label for="edit_km_stand">KM Stand:</label>
                            <input type="number" id="edit_km_stand" name="km_stand" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_name">Name:</label>
                            <input type="text" id="edit_name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_number">Number:</label>
                            <input type="text" id="edit_number" name="number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_last_used">Last Used:</label>
                            <input type="date" id="edit_last_used" name="last_used" class="form-control" required>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active">
                            <label class="form-check-label" for="edit_is_active">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Car</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Repair Management Modal -->
    <div class="modal fade" id="repairModal" tabindex="-1" role="dialog" aria-labelledby="repairModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="repairModalLabel">Repairs for <span id="carName"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="dashboard.php" id="repairForm">
                        <input type="hidden" name="add_repair" value="1">
                        <input type="hidden" id="car_id" name="car_id">
                        <div class="form-group">
                            <label for="repair_description">Repair Description:</label>
                            <textarea id="repair_description" name="repair_description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="repair_date">Repair Date:</label>
                            <input type="date" id="repair_date" name="repair_date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Repair</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </form>

                    <h5 class="mt-4">Existing Repairs:</h5>
                    <ul id="repairList" class="list-group">
                        <!-- Existing repairs will be loaded here via Jav<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</buttonaScript -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Activate Cars Modal -->
    <div class="modal fade" id="activateCarsModal" tabindex="-1" role="dialog" aria-labelledby="activateCarsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activateCarsModalLabel">Activate Cars</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="dashboard.php">
                    <div class="modal-body">
                        <input type="hidden" name="activate" value="1">
                        <?php foreach ($allCars as $car): ?>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="car_<?= $car['id'] ?>" name="car_ids[]" value="<?= $car['id'] ?>" <?= $car['is_active'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="car_<?= $car['id'] ?>"><?= htmlspecialchars($car['name']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Activate Selected Cars</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $('#editCarModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            $('#edit_id').val(button.data('id'));
            $('#edit_km_stand').val(button.data('km-stand'));
            $('#edit_name').val(button.data('name'));
            $('#edit_number').val(button.data('number'));
            $('#edit_last_used').val(button.data('last-used'));
            $('#edit_is_active').prop('checked', button.data('is-active') == 1);
        });

        $('#repairModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var carId = button.data('car-id');
            var carName = button.data('car-name');
            $('#car_id').val(carId);
            $('#carName').text(carName);
            loadRepairs(carId);
        });

        function loadRepairs(carId) {
            $.post('load_repairs.php', { car_id: carId }, function (data) {
                var repairList = $('#repairList');
                repairList.empty();
                data.repairs.forEach(function (repair) {
                    repairList.append(
                        '<li class="list-group-item">' +
                        '<strong>' + repair.repair_date + ':</strong> ' +
                        repair.repair_description +
                        '<form method="POST" action="dashboard.php" style="display:inline">' +
                        '<input type="hidden" name="delete_repair" value="1">' +
                        '<input type="hidden" name="id" value="' + repair.id + '">' +
                        '<button type="submit" class="btn btn-danger btn-sm float-right ml-2">Delete</button>' +
                        '</form>' +
                        '</li>'
                    );
                });
            }, 'json');
        }

        // Function to set today's date as the default value
        function setDefaultDate() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('repair_date').value = today;
        }

        // Call the function when the page loads
        window.onload = setDefaultDate;
    </script>
</body>
</html>
