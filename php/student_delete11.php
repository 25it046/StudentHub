<?php
require_once "db_mysqli.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/students11.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: ../pages/students11.php?message=" . urlencode("Invalid student ID."));
    exit;
}

$stmt = $conn->prepare("DELETE FROM student_records WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $message = "Student deleted successfully.";
} else {
    $message = "Student not found or deletion failed.";
}

$stmt->close();
$conn->close();

header("Location: ../pages/students11.php?message=" . urlencode($message));
exit;
?>