<?php
header("Content-Type: application/json");
include "db.php";

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate user_id
if (!isset($data['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "user_id is required"
    ]);
    exit;
}

$user_id = intval($data['user_id']);

// Fetch items for user
$query = "SELECT id, item_type, item_name, quantity, created_at
          FROM items
          WHERE user_id = ?
          ORDER BY created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$items = [];

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode([
    "status" => "success",
    "items" => $items
]);

$stmt->close();
$conn->close();
?>
