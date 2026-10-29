```php
<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");

    if ($name == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Please enter a valid name and email.");
    }

    $sql = "INSERT INTO students (name, email, mobile) VALUES (:name, :email, :mobile)";
    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ":name" => $name,
        ":email" => $email,
        ":mobile" => $mobile
    ]);

    echo "Student added successfully!";
} else {
    echo "Please submit the student form.";
}
?>
```