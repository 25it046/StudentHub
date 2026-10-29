<?php
require_once "db_mysqli.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Invalid event ID.");
}

$stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

$stmt->close();
$conn->close();

if (!$event) {
    exit("Event not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Edit Event</h2>

    <form action="event_update12.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $event["id"]; ?>">

        <label>Event Title:</label>
        <input type="hidden" name="id" value="<?php echo $event["event_id"]; ?>">
        <br><br>

        <label>Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($event["description"], ENT_QUOTES, "UTF-8"); ?></textarea>
        <br><br>

        <label>Event Date:</label>
        <input type="date" name="event_date" required
               value="<?php echo htmlspecialchars($event["event_date"], ENT_QUOTES, "UTF-8"); ?>">
        <br><br>

        <p>Current Poster:</p>

        <?php if ($event["poster"]): ?>
            <img src="../posters/<?php echo rawurlencode($event["poster"]); ?>"
                 width="120" alt="Current event poster">
        <?php else: ?>
            No poster uploaded.
        <?php endif; ?>

        <br><br>

        <label>Upload New Poster (optional; JPG, PNG, WEBP; maximum 2 MB):</label>
        <input type="file" name="poster" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        <br><br>

        <button type="submit">Update Event</button>
    </form>

    <br>
    <a href="../pages/events12.php">Back to Events</a>
</body>
</html>