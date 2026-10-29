<?php
require_once "db_mysqli.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/students11.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$course = trim($_POST["course"] ?? "");

if (!$id || $id < 1 || $name === "" ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) || $course === "") {
    header("Location: ../pages/students11.php?message=" . urlencode("Invalid student details."));
    exit;
}

$stmt = $conn->prepare(
    "UPDATE student_records SET name = ?, email = ?, course = ? WHERE id = ?"
);
$stmt->bind_param("sssi", $name, $email, $course, $id);

if ($stmt->execute()) {
    $message = $stmt->affected_rows > 0
        ? "Student updated successfully."
        : "No changes made, or student not found.";
    if ($stmt->errno !== 0) {
        $message = "Failed to update student.";
    }
} else {
    $message = $stmt->errno === 1062
        ? "This email already belongs to another student."
        : "Failed to update student.";
}

$stmt->close();
$conn->close();

header("Location: ../pages/students11.php?message=" . urlencode($message));
exit;
?>