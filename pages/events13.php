```php
<!DOCTYPE html>
<html>
<head>
    <title>Practical 13 - Event Management</title>
    <style>
        body { font-family: Arial; margin: 30px; background: #f4f4f4; }
        input, textarea, button { padding: 10px; margin: 5px 0; }
        table { width: 100%; background: white; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #ddd; }
    </style>
</head>
<body>

<h2>Event Management System - Practical 13</h2>

<form id="eventForm">
    <input type="hidden" id="event_id">
    <label>Event Name:</label><br>
    <input type="text" id="event_name" required><br>
    <label>Description:</label><br>
    <textarea id="description"></textarea><br>
    <label>Event Date:</label><br>
    <input type="date" id="event_date" required><br>
    <button type="submit">Add Event / Update Event</button>
    <button type="button" onclick="resetForm()">Cancel Edit</button>
</form>

<div id="message"></div>

<h2>All Events</h2>

<table>
<tr><th>ID</th><th>Event Name</th><th>Description</th><th>Date</th><th>Actions</th></tr>
<tbody id="eventList">

</tbody>
</table>

<script src="../js/events13.js"></script>
</body>
</html>
```