<?php
session_start();
include 'database.php'; 

if (!isset($_SESSION['username'])) {
    die("You must be logged in to register for an event.");
}

$student_id = $_SESSION['username'];
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

if ($event_id === 0) {
    die("Invalid event ID.");
}

$query = "SELECT event_name, ticket_price FROM event WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Event not found.");
}

$event = $result->fetch_assoc();
$event_name = htmlspecialchars($event['event_name']);
$ticket_price = htmlspecialchars($event['ticket_price']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Event</title>
    <link rel="stylesheet" href="create-event.css">
</head>
<body>
    <div class="container">
    <a onclick="history.back()" class="back-btn">←</a>
        <form id="eventForm" action="checkout2.php" method="POST">
        <h2>Register for Event</h2>
    
        <label>Student ID:</label>
        <input type="text" name="student_id" value="<?= $student_id ?>" readonly><br>

        <label>Event Name:</label>
        <input type="text" name="event_name[]" value="<?= $event_name ?>" readonly><br>

        <label>Event ID:</label>
<input type="text" name="event_id_display" value="<?= $event_id ?>" readonly><br>
<input type="hidden" name="event_id" value="<?= $event_id ?>">



        <label>Ticket Price (MYR):</label>
        <input type="text" name="price[]" value="<?= $ticket_price ?>" readonly><br>

        <label>Email:</label>
        <input type="email" name="email" required><br>

        <button type="submit" class="payment-btn" >Register</button>
    </form>
    </div>

    <script>
        function addMore() {
            let form = document.querySelector("form");
            let newFields = `
                <label>Event Name:</label>
        <input type="text" name="event_name[]" value="<?= $event_name ?>" readonly><br>
                <label>Ticket Price (MYR):</label>
        <input type="text" name="price[]" value="<?= $ticket_price ?>" readonly><br>
            `;
            form.insertAdjacentHTML("beforeend", newFields);
        }
    </script>
</body>
</html>
