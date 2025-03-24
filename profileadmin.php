<?php
session_start();
include 'database.php';

$user_id = $_SESSION['username']; 

$query = "SELECT * FROM admin WHERE staff_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc(); 

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
<a onclick="history.back()" class="back-btn">←</a>
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
            <input type="text" value="<?= htmlspecialchars($user['ph_num']) ?>" disabled>
        </div>
        <div class="form-group">
            <label>Staff ID</label>
            <input type="text" value="<?= htmlspecialchars($user['staff_id']) ?>" disabled>
        </div>
    </div>
    <form action="logout.php" method="POST">
        <button class="logout-btn">Log Out</button>
    </form>
</div>

</body>
</html>
