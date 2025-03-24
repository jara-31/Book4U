<?php
session_start();
include 'database.php';

$event_id = $_GET['event_id'];
$query = "SELECT * FROM event WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    die("Event not found!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book4U - Edit Event</title>
    <link rel="stylesheet" href="create-event.css">
    <style>
        .hidden { display: none; }
        .error { border: 2px solid red; } 
        .preview-img { margin-top: 10px; display: block; width: 200px; }
    </style>
</head>
<body>
<div class="container">
    <a onclick="history.back()" class="back-btn">←</a>
    <form id="eventForm" action="update_event.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="event_id" value="<?= htmlspecialchars($event['event_id']) ?>">

        <div id="step1">
            <h1>Book4U</h1>
            <h2>Edit Event</h2>

            <label>Event Title:</label>
            <input type="text" name="event_name" id="event_name" value="<?= htmlspecialchars($event['event_name']) ?>" required>

            <label>Event Venue:</label>
            <input type="text" name="venue" id="venue" value="<?= htmlspecialchars($event['venue']) ?>" required>

            <label>Time:</label>
            <input type="time" name="time" id="time" value="<?= htmlspecialchars($event['time']) ?>" required>

            <label>Event Date:</label>
            <input type="date" name="date" id="date" value="<?= htmlspecialchars($event['date']) ?>" required>

            <label>Event Description:</label>
            <textarea name="event_description" id="event_description"><?= htmlspecialchars($event['event_description']) ?></textarea>

            <label>Current Image:</label><br>
            <?php if (!empty($event['event_image'])): ?>
                <img src="<?= htmlspecialchars($event['event_image']) ?>" id="currentImage" alt="Event Image" class="preview-img">
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($event['event_image']) ?>">
            <?php else: ?>
                <p>No image available.</p>
            <?php endif; ?>

            <label>Upload New Image:</label>
            <input type="file" name="event_image" id="event_image" accept="image/*">
            <img id="previewImage" class="preview-img hidden">

            <button type="button" id="nextStep" class="payment-btn">Payment details</button>
        </div>

        <div id="step2" class="hidden">
            <h2>Book4U</h2>
            <h3>Edit Event</h3>


            <label>Price:</label>
            <input type="number" step="0.1" name="ticket_price" value="<?= htmlspecialchars($event['ticket_price']) ?>" required>

            <button type="button" id="prevStep" class="payment-btn">Back</button>
            <button type="submit" name="submit" class="payment-btn">Done</button>
        </div>
    </form>

    <script>
        document.getElementById('nextStep').addEventListener('click', function() {
            let valid = true;
            let fields = ['event_name', 'venue', 'time', 'date', 'event_description'];

            fields.forEach(function(field) {
                let input = document.getElementById(field);
                if (!input.value) {
                    input.classList.add('error'); 
                    valid = false;
                } else {
                    input.classList.remove('error');
                }
            });

            if (valid) {
                document.getElementById('step1').classList.add('hidden');
                document.getElementById('step2').classList.remove('hidden');
            } else {
                alert("Please fill in all fields before proceeding.");
            }
        });

        document.getElementById('prevStep').addEventListener('click', function() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
        });

        document.getElementById('event_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('previewImage');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>
</div>
</body>
</html>

