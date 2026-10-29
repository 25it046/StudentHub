```php
<?php
session_start();

$conn = new mysqli("localhost", "root", "", "studenthub");

if ($conn->connect_error) {
    die("Database connection failed");
}

if (!isset($_SESSION['user_id'])) {
    die("Please login first. <a href='../login.php'>Login</a>");
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT role, username FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || $user['role'] !== 'admin') {
    http_response_code(403);
    die("Access denied. Admin only.");
}

$username = $user['username'];

$users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$students = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];
$events = $conn->query("SELECT COUNT(*) AS total FROM events")->fetch_assoc()['total'];

$latest = $conn->query("SELECT student_id, name, email FROM students ORDER BY student_id DESC LIMIT 5");

$log = $conn->prepare("INSERT INTO audit_logs (user_id, action) VALUES (?, ?)");
$action = "Viewed admin dashboard";
$log->bind_param("is", $user_id, $action);
$log->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard - StudentHub</title>
<style>
body{font-family:Arial,sans-serif;background:#f3f4f6;margin:0;color:#222}
header{background:#183153;color:white;padding:20px}
nav{background:#24476b;padding:12px}
nav a{color:white;text-decoration:none;margin-right:20px}
.container{padding:25px}
.cards{display:flex;gap:20px;flex-wrap:wrap}
.card{background:white;padding:25px;border-radius:10px;box-shadow:0 2px 8px #ccc;min-width:150px;flex:1}
.number{font-size:30px;font-weight:bold;color:#245a9b}
table{width:100%;border-collapse:collapse;background:white;margin-top:15px}
th,td{padding:12px;border:1px solid #ddd;text-align:left}
th{background:#183153;color:white}
</style>
</head>
<body>
<header>
<h1>StudentHub Admin Dashboard</h1>
<p>Welcome, <?php echo htmlspecialchars($username); ?>!</p>
</header>

<nav>
<a href="admin_dashboard14.php">Dashboard</a>
<a href="events13.php">Manage Events</a>
<a href="../index.php">Home</a>
</nav>

<div class="container">
<h2>Dashboard Statistics</h2>
<div class="cards">
<div class="card">Total Users<div class="number"><?php echo $users; ?></div></div>
<div class="card">Total Students<div class="number"><?php echo $students; ?></div></div>
<div class="card">Total Events<div class="number"><?php echo $events; ?></div></div>
</div>

<h2>Latest Student Registrations</h2>
<table>
<tr><th>ID</th><th>Name</th><th>Email</th></tr>
<?php while ($row = $latest->fetch_assoc()) { ?>
<tr>
<td><?php echo htmlspecialchars($row['student_id']); ?></td>
<td><?php echo htmlspecialchars($row['name']); ?></td>
<td><?php echo htmlspecialchars($row['email']); ?></td>
</tr>
<?php } ?>
</table>

<h2>Audit Log</h2>
<p>Your dashboard visit has been recorded in the audit log.</p>
</div>
</body>
</html>
<?php $conn->close(); ?>
```