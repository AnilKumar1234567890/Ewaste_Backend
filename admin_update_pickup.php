<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['pickup_id']) || !isset($data['status'])) {
    echo json_encode([
        "status" => "error",
        "message" => "pickup_id and status required"
    ]);
    exit;
}

$pickup_id = $data['pickup_id'];
$status    = $data['status'];

$stmt = $conn->prepare("
    UPDATE pickups 
    SET status = ?
    WHERE id = ?
");

$stmt->bind_param("si", $status, $pickup_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Pickup status updated"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Update failed"
    ]);
}
?>
