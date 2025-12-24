<?php
include "db.php";

$token = $_GET['token'] ?? '';

$check = mysqli_query(
    $conn,
    "SELECT * FROM user_login WHERE reset_token='$token' AND reset_expiry > NOW()"
);

if (mysqli_num_rows($check) == 0) {
    die("Invalid or expired link");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query(
        $conn,
        "UPDATE user_login 
         SET password='$newPass', reset_token=NULL, reset_expiry=NULL
         WHERE reset_token='$token'"
    );

    echo "Password reset successful";
}
?>

<form method="POST">
    <input type="password" name="password" placeholder="New password" required />
    <button type="submit">Reset Password</button>
</form>
