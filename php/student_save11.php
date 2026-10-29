<?php
require_once "db_mysqli.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/students11.php");
    exit;
}

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$course = trim($_POST["course"] ?? "");

if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || $course === "") {
    header("Location: ../pages/students11.php?message=" . urlencode("Please enter valid student details."));
    exit;
}

$stmt = $conn->prepare("INSERT INTO student_records (name, email, course) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $course);

if ($stmt->execute()) {
    $message = "Student added successfully.";
} else {
    $message = $stmt->errno === 1062
        ? "This email already exists."
        : "Failed to add student.";
}

$stmt->close();
$conn->close();

header("Location: ../pages/students11.php?message=" . urlencode($message));
exit;
?>