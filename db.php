<?php
header("Content-Type: application/json");

$conn = mysqli_connect("localhost", "root", "", "ewaste");

if (!$conn) {
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed"
    ]);
    exit;
}
?>
