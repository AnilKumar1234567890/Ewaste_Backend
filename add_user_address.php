<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data['user_id']) ||
    !isset($data['address']) ||
    !isset($data['pincode'])
) {
    echo json_encode([
        "status" => "error",
        "message" => "Required fields missing"
    ]);
    exit;
}

$user_id = $data['user_id'];
$address = $data['address'];
$pincode = $data['pincode'];

$stmt = $conn->prepare(
    "INSERT INTO user_addresses (user_id, address, pincode)
     VALUES (?, ?, ?)"
);

$stmt->bind_param("iss", $user_id, $address, $pincode);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Address added successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to add address"
    ]);
}
?>
