<?php
require_once "../php/db_mysqli.php";

$message = $_GET["message"] ?? "";
$result = $conn->query("SELECT * FROM events ORDER BY event_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Event Management - Practical 12</title>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Event Management System</h2>

    <?php if ($message !== ""): ?>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>
    <?php endif; ?>

    <h3>Add Event</h3>

    <form action="../php/event_save12.php" method="POST" enctype="multipart/form-data">
        <label>Event Title:</label>
        <input type="text" name="title" maxlength="150" required>
        <br><br>

        <label>Description:</label>
        <textarea name="description" required></textarea>
        <br><br>

        <label>Event Date:</label>
        <input type="date" name="event_date" required>
        <br><br>

        <label>Poster (JPG, PNG, WEBP; maximum 2 MB):</label>
        <input type="file" name="poster" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        <br><br>

        <button type="submit">Add Event</button>
    </form>

    <hr>

    <h3>All Events</h3>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
            <th>Poster</th>
            <th>Actions</th>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["event_id"]; ?></td>
                    <td><?php echo htmlspecialchars($row["event_name"], ENT_QUOTES, "UTF-8"); ?></td>
                    <td><?php echo htmlspecialchars($row["description"], ENT_QUOTES, "UTF-8"); ?></td>
                    <td><?php echo htmlspecialchars($row["event_date"], ENT_QUOTES, "UTF-8"); ?></td>
                    <td>
                        <?php if ($row["poster"]): ?>
                            <img src="../posters/<?php echo rawurlencode($row["poster"]); ?>"
                                 width="100" alt="Event poster">
                        <?php else: ?>
                            No poster
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="../php/event_edit12.php?id=<?php echo $row["event_id"]; ?>">Edit</a>

                        <form action="../php/event_delete12.php" method="POST"
                              style="display:inline"
                              onsubmit="return confirm('Are you sure you want to delete this event?');">
                            <input type="hidden" name="id" value="<?php echo $row["event_id"]; ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No events found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>