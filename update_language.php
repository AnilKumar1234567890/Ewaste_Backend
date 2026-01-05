<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id']) || !isset($data['language'])) {
    echo json_encode([
        "status" => "error",
        "message" => "User ID or language missing"
    ]);
    exit;
}

$user_id = $data['user_id'];
$language = $data['language'];

$allowed = ["English", "Hindi", "Telugu", "Tamil"];
if (!in_array($language, $allowed)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid language"
    ]);
    exit;
}

$stmt = $conn->prepare("UPDATE user_login SET language=? WHERE id=?");
$stmt->bind_param("si", $language, $user_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Language updated successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to update language"
    ]);
}
?>
