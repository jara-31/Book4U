<?php
require_once 'database.php';

if (!isset($_GET['ticket_id'])) {
    die("Invalid ticket.");
}

$ticket_id = $_GET['ticket_id'];

$query = "SELECT * FROM ticket WHERE ticket_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Ticket not found.");
}

$ticket = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Ticket</title>
    <link rel="stylesheet" href="ticket.css"> 
</head>
<body>
    <a href="index.php" class="back-btn">←</a>
    <div class="ticket">
        <div class="success-icon">✅</div>
        <h2>Payment Success!</h2>
        <h3>RM<?= htmlspecialchars(number_format($ticket['price'], 2)); ?></h3>
        <hr>
        <p><strong>Ticket ID:</strong> <?= htmlspecialchars($ticket['ticket_id']); ?></p>
        <p><strong>Event ID:</strong> <?= htmlspecialchars($ticket['event_id']); ?></p>
        <p><strong>Student ID:</strong> <?= htmlspecialchars($ticket['student_id']); ?></p>
        <p><strong>Amount:</strong> RM<?= htmlspecialchars(number_format($ticket['price'], 2)); ?></p>
        <button class="print-button" onclick="window.print()">PRINT RECEIPT</button>
    </div>
</body>
</html>
