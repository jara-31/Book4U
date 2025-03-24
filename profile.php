<?php
session_start();
include 'database.php';

$user_id = $_SESSION['username']; 

$query = "SELECT * FROM user WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc(); 

$query = "SELECT * FROM event WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id); 
$stmt->execute();
$eventsResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 50%; margin: auto; }
        .event-list { list-style: none; padding: 0; }
        .event-list li { margin: 10px 0; }
        .event-list a { text-decoration: none; color: blue; }
        .logout-btn { background: red; color: white; padding: 10px; border: none; cursor: pointer; }
    </style>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <a href="index.php" class="back-btn">←</a>
    <div class="container">
    <div class="profile-header">
        <img src="Pictures/profile1.png" alt="Profile Picture">
        <div>
            <h3><?= isset($user['name']) ? $user['name'] : "No Name Found" ?></h3>
            <p><?= isset($user['email']) ? $user['email'] : "No Email Found" ?></p>
        </div>
    </div>

    <div class="form-container">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" value="<?= htmlspecialchars($user['name']) ?>" disabled>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" value="<?= htmlspecialchars($user['ph_number']) ?>" disabled>
        </div>
        <div class="form-group">
            <label>Student ID</label>
            <input type="text" value="<?= htmlspecialchars($user['student_id']) ?>" disabled>
        </div>
    </div>

    <div class="events-section">
        <h3>Events</h3>
        <?php while ($event = $eventsResult->fetch_assoc()): ?>
            <div class="event-item">
                <i class="fas fa-calendar-alt"></i>
                <a href="view_event.php?event_id=<?= $event['event_id'] ?>">
                    <?= $event['event_name'] ?>
                </a>
        </div>
        <?php endwhile; ?>
    </div>

    <form action="logout.php" method="POST">
        <button class="logout-btn">Log Out</button>
    </form>
</div>

</body>
</html>
