<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data['user_id'] ?? null;
$age     = $data['age'] ?? null;
$city    = $data['city'] ?? null;

if (!$user_id) {
    echo json_encode(["status"=>"error","message"=>"User ID required"]);
    exit;
}

$stmt = $conn->prepare("
    UPDATE user_login 
    SET age = ?, city = ? 
    WHERE id = ?
");
$stmt->bind_param("isi", $age, $city, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status"=>"success","message"=>"Profile updated"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Update failed"]);
}
?>
