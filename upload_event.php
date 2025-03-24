<?php
session_start();
include 'database.php'; 

$student_id = $_SESSION['username']; 

if (isset($_POST['submit'])) {
    $event_name = $_POST['event_name'];
    $venue = $_POST['venue'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $event_description = $_POST['event_description'];
    $ticket_price = $_POST['ticket_price'];

    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $image_name = basename($_FILES["event_image"]["name"]);
    $target_file = $target_dir . time() . "-" . $image_name;

    if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO event (student_id, event_name, venue, date, time, event_description, ticket_price, event_image) 
                VALUES ('$student_id', '$event_name', '$venue', '$date', '$time', '$event_description', '$ticket_price', '$target_file')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Event Created Successfully!');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Failed to upload image.');</script>";
    }
}
?>
