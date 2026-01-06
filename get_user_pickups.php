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

$sql = "
SELECT p.*, i.item_name
FROM pickups p
JOIN items i ON p.item_id = i.id
WHERE p.user_id = ?
ORDER BY p.created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $data
]);
?>
