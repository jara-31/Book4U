<?php
require_once 'database.php'; // Ensure database connection
require_once 'stripe-php-master/init.php';

// Ensure database connection
if (!$conn) {
    die("Database connection failed.");
}

// Initialize Stripe
$stripe = new \Stripe\StripeClient("sk_test_51R3G7yQtIfjrZpltKDTu2mDOvJ4mMfALg0FdHm44fW7xhKlPGcCQMd0WwV4sGBM9UUJTfLhVRVUcbVCDYHjvbd6b00TLz1kucD");

// Check if required parameters are present
if (!isset($_GET['session_id'], $_GET['student_id'], $_GET['event_id'], $_GET['email'])) {
    die("Invalid request. Missing parameters.");
}

$session_id = $_GET['session_id'];
$student_id = $_GET['student_id'];
$event_id = $_GET['event_id'];
$email = $_GET['email'];

// Debugging: Check if event_id is valid
if (empty($event_id) || !is_numeric($event_id)) {
    die("Invalid event_id: " . htmlspecialchars($event_id));
}

try {
    // Retrieve the payment session from Stripe
    $session = $stripe->checkout->sessions->retrieve($session_id);

    // Debugging: Output session data
    if (!$session) {
        die("Error: Could not retrieve Stripe session. Check session_id: " . htmlspecialchars($session_id));
    }

    echo "<pre>";
    print_r($session);
    echo "</pre>";

    // Check if payment was successful
    if ($session->payment_status === "paid") {
        // Retrieve ticket price from database
        $query = "SELECT ticket_price FROM event WHERE event_id = ?";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            die("Database error: " . $conn->error);
        }

        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            die("Error: Event not found in database.");
        }

        $event = $result->fetch_assoc();
        $ticket_price = $event['ticket_price'];

        // Insert registration details
        $insertRegistration = "INSERT INTO registration (student_id, event_id, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertRegistration);

        if (!$stmt) {
            die("Database error: " . $conn->error);
        }

        $stmt->bind_param("sis", $student_id, $event_id, $email);
        $stmt->execute();
        
        $registration_id = $stmt->insert_id; // Get the inserted registration ID

        // Insert ticket details
        $insertTicket = "INSERT INTO ticket (event_id, student_id, price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertTicket);

        if (!$stmt) {
            die("Database error: " . $conn->error);
        }

        $stmt->bind_param("isd", $event_id, $student_id, $ticket_price);
        $stmt->execute();
        
        $ticket_id = $stmt->insert_id;

        // Redirect to ticket page
        header("Location: ticket3.php?ticket_id=" . $ticket_id);
        exit;
    } else {
        die("Payment verification failed. Status: " . htmlspecialchars($session->payment_status));
    }
} catch (Exception $e) {
    error_log("Stripe error: " . $e->getMessage());
    die("Error processing payment: " . $e->getMessage());
}
?>
