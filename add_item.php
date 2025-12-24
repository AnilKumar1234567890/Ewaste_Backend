<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data['user_id']) ||
    !isset($data['item_type']) ||
    !isset($data['item_name']) ||
    !isset($data['quantity'])
) {
    echo json_encode([
        "status" => "error",
        "message" => "Required fields missing"
    ]);
    exit;
}

$user_id   = $data['user_id'];
$item_type = $data['item_type'];
$item_name = $data['item_name'];
$quantity  = $data['quantity'];

// 🔍 check user exists
$checkUser = $conn->prepare("SELECT id FROM user_login WHERE id=?");
$checkUser->bind_param("i", $user_id);
$checkUser->execute();
$result = $checkUser->get_result();

if ($result->num_rows == 0) {
    echo json_encode([
        "status" => "error",
        "message" => "User not found"
    ]);
    exit;
}

// ✅ insert item
$stmt = $conn->prepare(
    "INSERT INTO items (user_id, item_type, item_name, quantity)
     VALUES (?, ?, ?, ?)"
);

$stmt->bind_param("issi", $user_id, $item_type, $item_name, $quantity);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Item added successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to add item"
    ]);
}
?>
