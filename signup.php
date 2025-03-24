<?php
include 'database.php';

if (isset($_POST['submit'])) {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $ph_number = $_POST['ph_number'];

    $check_query = "SELECT * FROM user WHERE student_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                alert('Error: Student ID already exists! Please use a different ID.');
                window.location.href = 'signup.php';
              </script>";
    } else {
        $insert_query = "INSERT INTO user (student_id, name, password, email, ph_number) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sssss", $student_id, $name, $password, $email, $ph_number);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Sign up successful! Please log in.');
                    window.location.href = 'login2.php';
                  </script>";
        } else {
            echo "<script>alert('Error: Could not register. Please try again.');</script>";
        }
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet"href="login.css">
     <link rel="stylesheet" href="create-event.css">
</head>
<body>
    <a href="login2.php" class="back-btn">←</a>
    <div class="signup-container">
    <h2>Sign Up</h2>
    <form action="signup.php" method="POST" class="login">
        <table>
            <tr>
                <td><label>Student ID:</label><input type="text" name="student_id" placeholder = "Student id" required></td>
            </tr>
            <tr>
                <td><label>Name:</label>
                <input type="text" name="name"  placeholder = "Your Name" required ></td>
            </tr>
            <tr>
                <td><label>Password:</label><input type="password"name="password" placeholder="Password" required></td>
            </tr>
            <tr>
                <td><label>Email:</label>
                <input type="email" name="email" placeholder = "Your Email" required></td>
            </tr>
            <tr>
                <td><label>Phone Number:</label>
                <input type="text" name="ph_number" placeholder = "Phone Number" required></td>
            </tr>
        </table>
        <button class="signup" type="submit" name="submit">Sign Up</button>
    </form>
    </div>
</body>
</html>
