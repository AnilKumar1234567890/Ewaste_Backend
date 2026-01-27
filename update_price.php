<?php
include "db.php";
$data = json_decode(file_get_contents("php://input"), true);

$stmt = $conn->prepare(
"UPDATE price_rates SET price_per_kg=? WHERE item_type=?"
);
$stmt->bind_param("is",$data['price'],$data['item_type']);
$stmt->execute();

echo json_encode(["status"=>"success"]);
?>
