<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

// RAW values (do NOT hash here)
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if ($email === '' || $password === '') {
    echo json_encode([
        "status" => "error",
        "message" => "Email and password required"
    ]);
    exit;
}

// Prepared statement (SAFE)
$stmt = $conn->prepare(
    "SELECT id, email, password FROM admin_login WHERE email = ?"
);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo json_encode([
        "status" => "error",
        "message" => "Admin not found"
    ]);
    exit;
}

$admin = $result->fetch_assoc();

// 🔴 IMPORTANT: DO NOT hash $password again here
if (!password_verify($password, $admin['password'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid password"
    ]);
    exit;
}

// SUCCESS
echo json_encode([
    "status" => "successfully",
    "message" => "Admin login successful",
    "admin" => [
        "id" => $admin['id'],
        "email" => $admin['email']
    ]
]);
