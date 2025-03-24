<?php
require_once 'config2.php';
require_once 'stripe-php-master/init.php';

$stripe = new \Stripe\StripeClient('sk_test_51R3G7yQtIfjrZpltKDTu2mDOvJ4mMfALg0FdHm44fW7xhKlPGcCQMd0WwV4sGBM9UUJTfLhVRVUcbVCDYHjvbd6b00TLz1kucD');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['event_name'], $_POST['price'], $_POST['event_id'], $_POST['student_id'], $_POST['email'])) {
        die("Error: Missing required data.");
    }

    $productNames = $_POST['event_name'];
    $prices = $_POST['price'];
    $event_id = intval($_POST['event_id']); 
    $student_id = htmlspecialchars($_POST['student_id']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if ($event_id === 0) {
        die("Error: Invalid event ID.");
    }

    $lineItems = [];

    for ($i = 0; $i < count($productNames); $i++) {
        $productName = htmlspecialchars($productNames[$i]);
        $price = floatval($prices[$i]) * 100; 

        if ($price > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'myr',
                    'product_data' => ['name' => $productName],
                    'unit_amount' => $price,
                ],
                'quantity' => 1,
            ];
        }
    }

    try {
        $domain = "http://" . $_SERVER['HTTP_HOST'];

        $checkoutSession = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $domain . '/event/payment_success.php?session_id={CHECKOUT_SESSION_ID}&student_id=' . urlencode($student_id) . '&event_id=' . urlencode($event_id) . '&email=' . urlencode($email),
            'cancel_url' => $domain . '/event/index.php',
        ]);
        header('Content-Type: application/json');
        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkoutSession->url);
        exit;
    } catch (Exception $e) {
        error_log("Stripe Checkout Error: " . $e->getMessage());
        echo "<script>alert('Error processing payment.'); window.history.back();</script>";
        exit;
    }
}
?>
