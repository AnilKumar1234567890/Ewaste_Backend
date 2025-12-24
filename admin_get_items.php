<?php
header("Content-Type: application/json");
include "db.php";

// Fetch all items with user info
$sql = "SELECT 
            items.id,
            items.item_type,
            items.item_name,
            items.quantity,
            items.created_at,
            user_login.name,
            user_login.email
        FROM items
        JOIN user_login ON items.user_id = user_login.id
        ORDER BY items.created_at DESC";

$result = mysqli_query($conn, $sql);

$items = [];

while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}

echo json_encode([
    "status" => "success",
    "items" => $items
]);

mysqli_close($conn);
?>
