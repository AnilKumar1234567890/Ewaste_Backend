<?php
header("Content-Type: application/json");
include "db.php";

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate user_id
if (!isset($data['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "User ID required"
    ]);
    exit;
}

$user_id = $data['user_id'];

// Fetch disposal history
$stmt = $conn->prepare("
    SELECT 
        id,
        item_type,
        item_name,
        quantity,
        image_path,
        status,
        created_at
    FROM items
    WHERE user_id = ?
    ORDER BY created_at DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$history = [];

while ($row = $result->fetch_assoc()) {
    $history[] = [
        "item_id" => $row['id'],
        "item_type" => $row['item_type'],
        "item_name" => $row['item_name'],
        "quantity" => $row['quantity'],
        "image" => $row['image_path'],
        "status" => $row['status'],
        "date" => $row['created_at']
    ];
}

echo json_encode([
    "status" => "success",
    "data" => $history
]);
?>
