<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['email'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Email is required"
    ]);
    exit;
}

$email = $data['email'];

// Check email exists
$check = mysqli_query($conn, "SELECT * FROM user_login WHERE email='$email'");
if (mysqli_num_rows($check) == 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Email not registered"
    ]);
    exit;
}

// Generate token
$token = bin2hex(random_bytes(32));
$expiry = date("Y-m-d H:i:s", strtotime("+15 minutes"));

// Save token
mysqli_query(
    $conn,
    "UPDATE user_login SET reset_token='$token', reset_expiry='$expiry' WHERE email='$email'"
);

// Reset link
$link = "http://localhost/ewaste/reset_password.php?token=$token";

// Send mail
$subject = "Reset Your E-Waste Password";
$message = "Click the link to reset your password:\n\n$link";
$headers = "From: no-reply@ewaste.com";

mail($email, $subject, $message, $headers);

echo json_encode([
    "status" => "success",
    "message" => "Password reset link sent to email"
]);
