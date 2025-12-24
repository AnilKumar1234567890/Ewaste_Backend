<?php
header("Content-Type: application/json");
include "db.php";

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($data['item_id']) || !isset($data['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "item_id and user_id are required"
    ]);
    exit;
}

$item_id = intval($data['item_id']);
$user_id = intval($data['user_id']);

// Delete item (only if it belongs to the user)
$sql = "DELETE FROM items WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $item_id, $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Item deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Item not found or not authorized"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to delete item"
    ]);
}

$stmt->close();
$conn->close();
?>
