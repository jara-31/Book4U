<?php
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    $event_name = $_POST['event_name'];
    $venue = $_POST['venue'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $description = $_POST['event_description'];
    $ticket_price = $_POST['ticket_price'];

    // Image upload handling
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!empty($_FILES["event_image"]["name"])) {
        $image_name = basename($_FILES["event_image"]["name"]);
        $target_file = $target_dir . time() . "_" . $image_name;

        if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
        } else {
            echo "<script>alert('Error uploading image!'); window.history.back();</script>";
            exit();
        }
    } else {
        $query = "SELECT event_image FROM event WHERE event_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $event = $result->fetch_assoc();
        $target_file = $event['event_image'];
    }

    $query = "UPDATE event SET event_name=?, venue=?, time=?, date=?, event_description=?, 
              ticket_price=?, event_image=? WHERE event_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssi", $event_name, $venue, $time, $date, $description, 
                      $ticket_price, $target_file, $event_id);

    if ($stmt->execute()) {
        echo "<script>alert('Event updated successfully!'); window.location.href='index.php?event_id=$event_id';</script>";
    } else {
        echo "<script>alert('Error updating event!'); window.history.back();</script>";
    }
}
?>
