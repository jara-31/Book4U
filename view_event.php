<?php
session_start();
include 'database.php';

$event_id = $_GET['event_id'];

$query = "SELECT * FROM event WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$eventResult = $stmt->get_result();
$event = $eventResult->fetch_assoc();

if (!$event) {
    die("Event not found!");
}

$query = "SELECT * FROM registration WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$registrationsResult = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registrations</title>
        <link rel="stylesheet" href="view_event.css">

</head>
<body>
    <a href="profile.php" class="back-btn">←</a>
    <div class="container">
        <h2><?= htmlspecialchars($event['event_name']) ?></h2>

        <h3>Registrations</h3>
        <table>
            <tr>
                <th>Registration ID</th>
                <th>Student ID</th>
                <th>Email</th>
            </tr>
            <?php while ($registration = $registrationsResult->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($registration['registration_id']) ?></td>
                    <td><?= htmlspecialchars($registration['student_id']) ?></td>
                    <td><?= htmlspecialchars($registration['email']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <a href="edit_event.php?event_id=<?= $event['event_id'] ?>">
            <button class="edit-btn">Edit Event</button>
        </a>
    </div>
</body>
</html>

