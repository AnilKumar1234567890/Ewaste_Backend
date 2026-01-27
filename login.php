<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['email']) || empty($data['password'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Email and password required"
    ]);
    exit;
}

$email = $data['email'];
$password = $data['password'];

// ** THE FIX IS HERE: Using prepared statements **
$stmt = $conn->prepare("SELECT * FROM user_login WHERE email = ?");
$stmt->bind_param("s", $email); // 's' for string
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Email not registered"
    ]);
    exit;
}

$user = $result->fetch_assoc();

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

$stmt->close();
$conn->close();
?>