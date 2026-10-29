<?php
require_once "../php/db_mysqli.php";

$message = $_GET["message"] ?? "";
$search = trim($_GET["search"] ?? "");
$course = trim($_GET["course"] ?? "");

$sql = "SELECT * FROM student_records WHERE 1=1";
$params = [];
$types = "";

if ($search !== "") {
    $sql .= " AND (name LIKE ? OR email LIKE ?)";
    $value = "%$search%";
    $params[] = $value;
    $params[] = $value;
    $types .= "ss";
}

if ($course !== "") {
    $sql .= " AND course = ?";
    $params[] = $course;
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$courses = $conn->query("SELECT DISTINCT course FROM student_records ORDER BY course");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Management - Practical 11</title>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Student Management System</h2>

    <?php if ($message !== ""): ?>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>
    <?php endif; ?>

    <h3>Add Student</h3>

    <form action="../php/student_save11.php" method="POST">
        <label>Name:</label>
        <input type="text" name="name" required maxlength="100">

        <label>Email:</label>
        <input type="email" name="email" required maxlength="100">

        <label>Course:</label>
        <input type="text" name="course" required maxlength="100">

        <button type="submit">Add Student</button>
    </form>

    <hr>

    <h3>Search and Filter Students</h3>

    <form method="GET">
        <input type="text" name="search"
               placeholder="Search name or email"
               value="<?php echo htmlspecialchars($search, ENT_QUOTES, "UTF-8"); ?>">

        <select name="course">
            <option value="">All Courses</option>

            <?php while ($c = $courses->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($c["course"], ENT_QUOTES, "UTF-8"); ?>"
                    <?php echo $course === $c["course"] ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($c["course"], ENT_QUOTES, "UTF-8"); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Search / Filter</button>
        <a href="students11.php">Clear</a>
    </form>

    <h3>Student Records</h3>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Course</th>
            <th>Actions</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo htmlspecialchars($row["name"], ENT_QUOTES, "UTF-8"); ?></td>
                    <td><?php echo htmlspecialchars($row["email"], ENT_QUOTES, "UTF-8"); ?></td>
                    <td><?php echo htmlspecialchars($row["course"], ENT_QUOTES, "UTF-8"); ?></td>
                    <td>
                        <a href="../php/student_edit11.php?id=<?php echo $row["id"]; ?>">Edit</a>

                        <form action="../php/student_delete11.php" method="POST"
                              style="display:inline"
                              onsubmit="return confirm('Delete this student?');">
                            <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No student records found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>