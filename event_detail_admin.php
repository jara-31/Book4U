<?php
include 'database.php';

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    $query = "SELECT * FROM event WHERE event_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $event_result = $stmt->get_result();
    $event = $event_result->fetch_assoc();

    $reg_query = "SELECT registration_id, student_id, email FROM registration WHERE event_id = ?";
    $stmt = $conn->prepare($reg_query);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $reg_result = $stmt->get_result();
} else {
    echo "<script>alert('Event not found.'); window.location.href = 'admin_events.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <link rel="stylesheet" href="view_event.css">
</head>
<body>

<a onclick="history.back()" class="back-btn">←</a>
<div class="container">
    <h2><?= htmlspecialchars($event['event_name']); ?></h2>

    <table>
        <tr>
            <th>Venue</th>
            <td><?= htmlspecialchars($event['venue']); ?></td>
        </tr>
        <tr>
            <th>Date</th>
            <td><?= htmlspecialchars($event['date']); ?></td>
        </tr>
        <tr>
            <th>Time</th>
            <td><?= htmlspecialchars($event['time']); ?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?= htmlspecialchars($event['event_description']); ?></td>
        </tr>
    </table>

    <h3>Registered Users</h3>

    <table>
        <tr>
            <th>Registration ID</th>
            <th>Student ID</th>
            <th>Email</th>
        </tr>
        <?php while ($reg = $reg_result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($reg['registration_id']); ?></td>
                <td><?= htmlspecialchars($reg['student_id']); ?></td>
                <td><?= htmlspecialchars($reg['email']); ?></td>
            </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>
