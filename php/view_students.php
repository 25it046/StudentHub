```php
<?php
require_once "db.php";

$stmt = $conn->prepare("SELECT student_id, name, email, mobile FROM students");
$stmt->execute();

$students = $stmt->fetchAll();

echo "<h2>Student Records</h2>";

foreach ($students as $student) {
    echo htmlspecialchars($student["student_id"]) . " - ";
    echo htmlspecialchars($student["name"]) . " - ";
    echo htmlspecialchars($student["email"]) . " - ";
    echo htmlspecialchars($student["mobile"] ?? "") . "<br>";
}
?>
```