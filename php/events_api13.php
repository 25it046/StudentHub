```php
<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "studenthub");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "GET") {
    $result = $conn->query("SELECT * FROM events ORDER BY event_id DESC");
    $events = [];

    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    echo json_encode($events);
} elseif ($method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = trim($data["event_name"] ?? "");
    $description = trim($data["description"] ?? "");
    $date = $data["event_date"] ?? "";

    if ($name == "" || $date == "") {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Event name and date are required"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO events (event_name, description, event_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $description, $date);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Event added successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to add event"]);
    }
} elseif ($method == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = (int)($data["event_id"] ?? 0);
    $name = trim($data["event_name"] ?? "");
    $description = trim($data["description"] ?? "");
    $date = $data["event_date"] ?? "";

    if ($id <= 0 || $name == "" || $date == "") {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Valid ID, event name, and date are required"]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE events SET event_name = ?, description = ?, event_date = ? WHERE event_id = ?");
    $stmt->bind_param("sssi", $name, $description, $date, $id);

    if ($stmt->execute() && $stmt->affected_rows >= 0) {
        echo json_encode(["success" => true, "message" => "Event updated successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to update event"]);
    }
} elseif ($method == "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = (int)($data["event_id"] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Valid event ID is required"]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Event deleted successfully"]);
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Event not found"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
}

$conn->close();
?>
```