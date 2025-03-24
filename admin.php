<?php
include 'database.php';

$sql = "SELECT * FROM event ORDER BY event_id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book4U - Home</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <div class="container">
        <nav>
            <h1 class="logo">Book4U</h1>
            <div class="profile">
                <a href="profileadmin.php">
                    <img src="Pictures/profile1.png" alt="Profile Picture">
                </a>
            </div>
        </nav>
        <div class="hero">
            <h2>Welcome Admin</h2>
        </div>
        <section class="events">
            <?php while ($event = $result->fetch_assoc()) { 
                $image_path = !empty($event['event_image']) ? htmlspecialchars($event['event_image']) : "default.png";
            ?>
            <div class="event-card" onclick="redirectToDetails(<?php echo $event['event_id']; ?>)">
                <img src="<?php echo $image_path; ?>" alt="Event Image">
                <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
                <div class="event-actions">
                    <a href="event_detail_admin.php?event_id=<?php echo $event['event_id']; ?>">
                        <button class="details-btn">View Details</button>
                    </a>
                </div>
            </div>
            <?php } ?>
        </section>
    </div>

    <script>
        function redirectToDetails(eventId) {
            window.location.href = `event_detail_admin.php?event_id=${eventId}`;
        }
    </script>
</body>
</html>
