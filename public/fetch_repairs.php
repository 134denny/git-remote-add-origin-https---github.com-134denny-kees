<?php
include_once '../config/db.php';

$car_id = $_GET['car_id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM repairs WHERE car_id = ?");
$stmt->execute([$car_id]);
$repairs = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($repairs);
?>
