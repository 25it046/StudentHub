
<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "studenthub";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed. Please try again later.");
}

$conn->set_charset("utf8mb4");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Please submit the registration form.");
}

$username = trim($_POST["username"] ?? "");
$email = trim($_POST["email"] ?? "");
$plainPassword = $_POST["password"] ?? "";

if (!preg_match("/^[a-zA-Z0-9_]{3,50}$/", $username)) {
    exit("Username must be 3-50 characters and contain only letters, numbers, and underscores.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
    exit("Please enter a valid email address.");
}

if (strlen($plainPassword) < 8) {
    exit("Password must contain at least 8 characters.");
}

$check = $conn->prepare(
    "SELECT user_id FROM users WHERE username = ? OR email = ?"
);
$check->bind_param("ss", $username, $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $check->close();
    $conn->close();
    exit("Username or email already exists. Please use different details.");
}

$check->close();

$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $username, $email, $hashedPassword);

if ($stmt->execute()) {
    echo "<h2>Registration Successful!</h2>";
    echo "<p>Your account has been created successfully.</p>";
} else {
    if ($stmt->errno == 1062) {
        echo "Username or email already exists. Please use different details.";
    } else {
        echo "Registration failed. Please try again.";
    }
}

$stmt->close();
$conn->close();
?>
