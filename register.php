<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (
    empty($data['name']) ||
    empty($data['email']) ||
    empty($data['password'])
) {
    echo json_encode([
        "status" => "error",
        "message" => "All fields are required"
    ]);
    exit;
}

$name = $data['name'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);

// check email exists
$check = mysqli_query($conn, "SELECT id FROM user_login WHERE email='$email'");
if (mysqli_num_rows($check) > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Email already exists"
    ]);
    exit;
}

$query = "INSERT INTO user_login (name, email, password) VALUES ('$name','$email','$password')";
if (mysqli_query($conn, $query)) {
    echo json_encode([
        "status" => "success",
        "message" => "Registration successful"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Registration failed"
    ]);
}
