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

if (isset($_SESSION["user_id"])) {
    $id = $_SESSION["user_id"];

    $stmt = $conn->prepare(
        "UPDATE users
         SET remember_token_hash = NULL, remember_expiry = NULL
         WHERE user_id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

setcookie("remember_token", "", [
    "expires" => time() - 3600,
    "path" => "/StudentHub/",
    "httponly" => true,
    "secure" => !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off",
    "samesite" => "Lax"
]);

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(session_name(), "", [
        "expires" => time() - 42000,
        "path" => $params["path"],
        "domain" => $params["domain"],
        "secure" => $params["secure"],
        "httponly" => $params["httponly"],
        "samesite" => "Lax"
    ]);
}

session_destroy();
$conn->close();

header("Location: ../pages/login10.html?loggedout=1");
exit;
?>