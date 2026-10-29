<?php
require_once "db_mysqli.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/events12.php");
    exit;
}

$title = trim($_POST["title"] ?? "");
$description = trim($_POST["description"] ?? "");
$date = $_POST["event_date"] ?? "";

if ($title === "" || $description === "" || $date === "") {
    exit("Please fill all event details.");
}

$poster = null;

if (isset($_FILES["poster"]) && $_FILES["poster"]["error"] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES["poster"]["error"] !== UPLOAD_ERR_OK) {
        exit("Poster upload failed.");
    }

    if ($_FILES["poster"]["size"] > 2 * 1024 * 1024) {
        exit("Poster size must be 2 MB or less.");
    }

    $allowed = ["image/jpeg", "image/png", "image/webp"];
    $mime = mime_content_type($_FILES["poster"]["tmp_name"]);

    if (!in_array($mime, $allowed, true)) {
        exit("Only JPG, PNG, and WEBP posters are allowed.");
    }

    $ext = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp"
    ][$mime];

    $folder = "../posters/";

    if (!is_dir($folder) && !mkdir($folder, 0755, true)) {
        exit("Could not create poster folder.");
    }

    $poster = bin2hex(random_bytes(16)) . "." . $ext;

    if (!move_uploaded_file($_FILES["poster"]["tmp_name"], $folder . $poster)) {
        exit("Could not save poster.");
    }
}

$stmt = $conn->prepare(
    "INSERT INTO events (event_name, description, event_date, poster) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("ssss", $title, $description, $date, $poster);

if ($stmt->execute()) {
    $message = "Event added successfully.";
} else {
    if ($poster !== null && is_file("../posters/" . $poster)) {
        unlink("../posters/" . $poster);
    }
    $message = "Failed to add event.";
}

$stmt->close();
$conn->close();

header("Location: ../pages/events12.php?message=" . urlencode($message));
exit;
?>