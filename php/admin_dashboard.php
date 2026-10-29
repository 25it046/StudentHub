<?php
require "auth.php";

if ($_SESSION["role"] !== "admin") {
    header("Location: student_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION["username"], ENT_QUOTES, "UTF-8"); ?>!</p>
    <p>You are logged in as an administrator.</p>
    <a href="logout.php">Logout</a>
</body>
</html>