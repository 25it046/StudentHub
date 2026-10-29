<?php
$conn = new mysqli("localhost", "root", "", "studenthub");

if ($conn->connect_error) {
    die("Database connection failed");
}

$username = "yashvi";
$password = password_hash("Yashvi@123", PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = ?, role = 'admin' WHERE username = ?");
$stmt->bind_param("ss", $password, $username);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo "Password reset successful. Your temporary password is Yashvi@123";
} else {
    echo "No user updated. Check the username.";
}

$stmt->close();
$conn->close();
?>