<?php
header("Content-Type: application/json");
include "db.php";

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode([
        "status" => "error",
        "message" => "User ID required"
    ]);
    exit;
}

$result = $conn->query(
    "SELECT id, address, pincode 
     FROM user_addresses 
     WHERE user_id = $user_id"
);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $data
]);
?>
