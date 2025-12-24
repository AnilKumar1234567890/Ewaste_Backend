<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['item_id']) || !isset($data['status'])) {
    echo json_encode([
        "status" => "error",
        "message" => "item_id and status are required"
    ]);
    exit;
}

$item_id = intval($data['item_id']);
$status  = strtoupper($data['status']);

// Allow only valid status values
$allowed = ['PENDING', 'APPROVED', 'REJECTED'];
if (!in_array($status, $allowed)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid status value"
    ]);
    exit;
}

// Update item status
$sql = "UPDATE items SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $item_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Item status updated successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to update item status"
    ]);
}

$stmt->close();
$conn->close();
?>
