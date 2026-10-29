<?php
require "auth.php";

if ($_SESSION["role"] !== "student") {
    header("Location: admin_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
</head>
<body>
    <h2>Student Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION["username"], ENT_QUOTES, "UTF-8"); ?>!</p>
    <p>You are logged in as a student.</p>
    <a href="logout.php">Logout</a>
</body>
</html>