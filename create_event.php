<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book4U - Create Event</title>
    <link rel="stylesheet" href="create-event.css">

    <style>
        .hidden { display: none; }
        .error { border: 2px solid red; } 
        #imagePreview { display: none; margin-top: 10px; }
    </style>
</head>
<body>
<div class="container">
    <a href="index.php" class="back-btn">←</a>
    <form id="eventForm" action="upload_event.php" method="POST" enctype="multipart/form-data">
        <div id="step1">
            <h1>Book4U</h1>
            <h2>Create Event</h2>

            <label>Event Title:</label>
            <input type="text" name="event_name" id="event_name" required>

            <label>Event Venue:</label>
            <input type="text" name="venue" id="venue" required>

            <label>Time:</label>
            <input type="time" name="time" id="time" required>

            <label>Event Date:</label>
            <input type="date" name="date" id="date" required>

            <label>Event Description:</label>
            <textarea name="event_description" id="event_description"></textarea>

            <label>Upload Image:</label>
            <input type="file" name="event_image" id="event_image" accept="image/*" required>
            
            <img id="imagePreview" src="#" alt="Image Preview" width="200">

            <button type="button" id="nextStep" class="payment-btn">Payment details</button>
        </div>

        <div id="step2" class="hidden">
            <h2>Book4U</h2>
            <h3>Create Event</h3>

            <label>Price:</label>
            <input type="number" step="0.1" name="ticket_price" required>

            <button type="button" id="prevStep" class="payment-btn">Back</button>
            <button type="submit" name="submit" class="payment-btn">Create Event</button>
        </div>
    </form>

    <script>
        document.getElementById('nextStep').addEventListener('click', function() {
            let valid = true;
            let fields = ['event_name', 'venue', 'time', 'date', 'event_description', 'event_image'];

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
            let reader = new FileReader();
            reader.onload = function() {
                let img = document.getElementById('imagePreview');
                img.src = reader.result;
                img.style.display = "block"; 
            };
            if (event.target.files.length > 0) {
                reader.readAsDataURL(event.target.files[0]);
            }
        });
    </script>
</div>
</body>
</html>
