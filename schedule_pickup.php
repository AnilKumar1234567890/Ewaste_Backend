<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data['user_id']) ||
    !isset($data['item_id']) ||
    !isset($data['pickup_address']) ||
    !isset($data['pickup_date']) ||
    !isset($data['pickup_time'])
) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing required fields"
    ]);
    exit;
}

$user_id = $data['user_id'];
$item_id = $data['item_id'];
$address = $data['pickup_address'];
$date    = $data['pickup_date'];
$time    = $data['pickup_time'];

/* Check user exists */
$checkUser = $conn->prepare("SELECT id FROM user_login WHERE id=?");
$checkUser->bind_param("i", $user_id);
$checkUser->execute();
if ($checkUser->get_result()->num_rows == 0) {
    echo json_encode(["status"=>"error","message"=>"User not found"]);
    exit;
}

/* Check item exists */
$checkItem = $conn->prepare("SELECT id FROM items WHERE id=?");
$checkItem->bind_param("i", $item_id);
$checkItem->execute();
if ($checkItem->get_result()->num_rows == 0) {
    echo json_encode(["status"=>"error","message"=>"Item not found"]);
    exit;
}

/* Insert pickup */
$stmt = $conn->prepare("
    INSERT INTO pickups (user_id, item_id, pickup_address, pickup_date, pickup_time)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param("iisss", $user_id, $item_id, $address, $date, $time);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Pickup scheduled successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to schedule pickup"
    ]);
}
?>
