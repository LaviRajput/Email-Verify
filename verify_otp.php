<?php
session_start();

// Database connection
$localhost = "localhost";
$username = "root";
$password = ""; 
$db_name = "contact_form";
$conn = mysqli_connect($localhost, $username, $password, $db_name);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_otp = trim($_POST['otp']);
    $stored_otp = $_SESSION['otp'] ?? null;
    $email = $_SESSION['email'] ?? null;

    if ($email) {
        if (empty($user_otp)) {
            // Blank OTP case
            $sql = "UPDATE contact_submission SET verification_status = 'unverified' WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            echo "<p style='color: red;'>No OTP entered. Verification failed.</p>";
        } elseif ($user_otp == $stored_otp) {
            // Correct OTP case
            $sql = "UPDATE contact_submission SET verification_status = 'verified' WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                echo "<p style='color: green;'>OTP verified successfully!</p>";

                // Clear session variables
                unset($_SESSION['otp']);
                unset($_SESSION['email']);

                header("Location: success.php");
                exit();
            } else {
                echo "<p style='color: red;'>Failed to update verification status. Please try again.</p>";
            }
        } else {
            // Incorrect OTP case
            $sql = "UPDATE contact_submission SET verification_status = 'unverified' WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            echo "<p style='color: red;'>Invalid OTP. Verification failed.</p>";
        }
    } else {
        echo "<p style='color: red;'>OTP or email session data not found. Please request OTP again.</p>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
</head>
<body>
    <center>
        <h1>Verify OTP</h1>
        <div class="message" style="color: green; font-size: 1.5rem;">
            <p>OTP sent to your email!</p>
        </div>
        <form method="POST" action="">
            <label for="otp">Enter OTP:</label>
            <input type="text" id="otp" name="otp" required>
            <button type="submit">Verify</button>
        </form>
    </center>
</body>
</html>
