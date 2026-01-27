<?php
header("Content-Type: application/json");
include "db.php";

$sql = "SELECT item_type, price_per_unit, unit FROM price_rates";
$result = $conn->query($sql);

$rates = [];

while ($row = $result->fetch_assoc()) {
    $rates[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $rates
]);
?>
