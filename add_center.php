<?php
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $conn->prepare(
"INSERT INTO recycling_centers (name,address,latitude,longitude,base_price,rating,is_authorized)
 VALUES (?,?,?,?,?,?,1)"
);

$stmt->bind_param(
"ssddid",
$data['name'],
$data['address'],
$data['latitude'],
$data['longitude'],
$data['base_price'],
$data['rating']
);

$stmt->execute();

echo json_encode(["status"=>"success"]);
?>
