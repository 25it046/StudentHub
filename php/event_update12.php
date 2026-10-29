<?php
require_once "db_mysqli.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/events12.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$title = trim($_POST["title"] ?? "");
$description = trim($_POST["description"] ?? "");
$date = $_POST["event_date"] ?? "";

if (!$id || $id < 1 || $title === "" || $description === "" || $date === "") {
    exit("Invalid event details.");
}

$stmt = $conn->prepare("SELECT poster FROM events WHERE event_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$existing) {
    exit("Event not found.");
}

$poster = $existing["poster"];
$newPoster = null;

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

    $newPoster = bin2hex(random_bytes(16)) . "." . $ext;

    if (!move_uploaded_file($_FILES["poster"]["tmp_name"], $folder . $newPoster)) {
        exit("Could not save new poster.");
    }

    $poster = $newPoster;
}

$stmt = $conn->prepare(
    "UPDATE events SET event_name = ?, description = ?, event_date = ?, poster = ? WHERE event_id = ?"
);
$stmt->bind_param("ssssi", $title, $description, $date, $poster, $id);

if ($stmt->execute()) {
    if ($newPoster !== null && $existing["poster"] !== null) {
        $oldPath = "../posters/" . $existing["poster"];
        if (is_file($oldPath)) {
            unlink($oldPath);
        }
    }
    $message = "Event updated successfully.";
} else {
    if ($newPoster !== null && is_file("../posters/" . $newPoster)) {
        unlink("../posters/" . $newPoster);
    }
    $message = "Failed to update event.";
}

$stmt->close();
$conn->close();

header("Location: ../pages/events12.php?message=" . urlencode($message));
exit;
?>