<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (
    empty($data['email']) ||
    empty($data['password'])
) {
    echo json_encode([
        "status" => "error",
        "message" => "Email and password required"
    ]);
    exit;
}

$email = $data['email'];
$password = $data['password'];

$query = "SELECT * FROM user_login WHERE email='$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Email not registered"
    ]);
    exit;
}

$user = mysqli_fetch_assoc($result);

if (password_verify($password, $user['password'])) {
    echo json_encode([
        "status" => "success",
        "message" => "Login successful",
        "user_id" => $user['id'],
        "name" => $user['name']
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid password"
    ]);
}
