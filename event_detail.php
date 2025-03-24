<?php
include 'database.php'; 

if (!isset($_GET['event_id'])) {
    die("Invalid event. No event_id found.");
}

$event_id = intval($_GET['event_id']); 

$query = "SELECT event_name, venue, time, date, event_description, event_image, ticket_price FROM event WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Event not found.";
    exit;
}

$event = $result->fetch_assoc();

$image_path = !empty($event['event_image']) ? htmlspecialchars($event['event_image']) : "default.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['event_name']); ?> - Event Details</title>
    <link rel="stylesheet" href="event.css"> 
</head>
<body>
<a href="index.php" class="back-btn">←</a>
<div class="container">
    <h1><?php echo htmlspecialchars($event['event_name']); ?></h1>
    <img src="<?php echo $image_path; ?>" alt="Event Image">
    
    <div class="event-details">
        <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
        <p><strong>Time:</strong> <?php echo htmlspecialchars($event['time']); ?></p>
    </div>

    <div class="event-details">
        <p><strong>Date:</strong> <?php echo htmlspecialchars($event['date']); ?></p>
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($event['event_description'])); ?></p>
    </div>

     <p class="ticket-price">Ticket Price: <strong>RM <?php echo number_format(htmlspecialchars($event['ticket_price']), 2); ?></strong></p>

    <a href="create_event3.php?event_id=<?php echo $event_id; ?>" class="register-btn">Register</a>
</div>

</body>
</html>
