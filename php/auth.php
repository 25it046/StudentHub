<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set("session.use_strict_mode", "1");
    ini_set("session.use_only_cookies", "1");

    session_set_cookie_params([
        "httponly" => true,
        "secure" => !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off",
        "samesite" => "Lax"
    ]);

    session_start();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: ../pages/login10.html");
    exit;
}

if (isset($_SESSION["last_activity"]) &&
    time() - $_SESSION["last_activity"] > 900) {
    $_SESSION = [];
    session_destroy();

    header("Location: ../pages/login10.html?timeout=1");
    exit;
}

$_SESSION["last_activity"] = time();
?>