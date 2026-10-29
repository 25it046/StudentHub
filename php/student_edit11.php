<?php
require_once "db_mysqli.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Invalid student ID.");
}

$stmt = $conn->prepare("SELECT * FROM student_records WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

if (!$student) {
    exit("Student not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>
    <h2>Edit Student</h2>

    <form action="student_update11.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $student["id"]; ?>">

        <label>Name:</label>
        <input type="text" name="name" maxlength="100" required
               value="<?php echo htmlspecialchars($student["name"], ENT_QUOTES, "UTF-8"); ?>">
        <br><br>

        <label>Email:</label>
        <input type="email" name="email" maxlength="100" required
               value="<?php echo htmlspecialchars($student["email"], ENT_QUOTES, "UTF-8"); ?>">
        <br><br>

        <label>Course:</label>
        <input type="text" name="course" maxlength="100" required
               value="<?php echo htmlspecialchars($student["course"], ENT_QUOTES, "UTF-8"); ?>">
        <br><br>

        <button type="submit">Update Student</button>
    </form>

    <br>
    <a href="../pages/students11.php">Back to Student List</a>
</body>
</html>