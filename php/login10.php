<?php
require "db_mysqli.php";

ini_set("session.use_strict_mode", "1");
ini_set("session.use_only_cookies", "1");

session_set_cookie_params([
    "httponly" => true,
    "secure" => !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off",
    "samesite" => "Lax"
]);

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/login10.html");
    exit;
}

$identity = trim($_POST["identity"] ?? "");
$password = $_POST["password"] ?? "";

if ($identity === "" || $password === "") {
    exit("Username/email and password are required.");
}

$stmt = $conn->prepare(
    "SELECT user_id, username, email, password, role
     FROM users
     WHERE username = ? OR email = ?
     LIMIT 1"
);

$stmt->bind_param("ss", $identity, $identity);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($password, $user["password"])) {
    http_response_code(401);
    exit("Invalid username/email or password.");
}

session_regenerate_id(true);

$_SESSION["user_id"] = $user["user_id"];
$_SESSION["username"] = $user["username"];
$_SESSION["role"] = $user["role"];
$_SESSION["last_activity"] = time();

if (isset($_POST["remember"]) && $_POST["remember"] === "1") {
    $token = bin2hex(random_bytes(32));
    $tokenHash = hash("sha256", $token);
    $expiry = date("Y-m-d H:i:s", time() + 30 * 24 * 60 * 60);

    $stmt = $conn->prepare(
        "UPDATE users
         SET remember_token_hash = ?, remember_expiry = ?
         WHERE user_id = ?"
    );

    $stmt->bind_param("ssi", $tokenHash, $expiry, $user["user_id"]);
    $stmt->execute();
    $stmt->close();

    setcookie("remember_token", $user["user_id"] . ":" . $token, [
        "expires" => time() + 30 * 24 * 60 * 60,
        "path" => "/StudentHub/",
        "httponly" => true,
        "secure" => !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off",
        "samesite" => "Lax"
    ]);
}

$conn->close();

if ($user["role"] === "admin") {
    header("Location: admin_dashboard.php");
} else {
    header("Location: student_dashboard.php");
}

exit;
?>