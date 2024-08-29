<?php
session_start();
include_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
    $car_id = intval($_POST['car_id']);

    $stmt = $pdo->prepare("SELECT * FROM repairs WHERE car_id = ?");
    $stmt->execute([$car_id]);
    $repairs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['repairs' => $repairs]);
}
?>