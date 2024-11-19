<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function sendEmailNotification($full_name, $phone_number, $email, $message, $otp = null) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Username = 'lavirajput7839@gmail.com';
    $mail->Password = 'idvnkfztnguiyeze';

    // Set sender and recipient
    $mail->setFrom('your-email@gmail.com', 'Lavi Rajput');
    $mail->addAddress($email, $full_name);  // Send OTP to user’s email

    // Set email subject and body
    if ($otp) {
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Hello $full_name,\n\nYour OTP is: $otp\n\nPlease enter this code to verify your identity.";
    } else {
        $mail->Subject = 'New Form Submission';
        $mail->Body = "New form submission:\n\nFull Name: $full_name\nPhone Number: $phone_number\nEmail: $email\nMessage: $message";
    }

    return $mail->send();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = array();
    $required_fields = array("full_name", "phone_number", "email", "message");

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = ucfirst($field) . " is required.";
        }
    }

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format.";
    }

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        include "index.html";
        exit;
    }

    // Connect to the database
    $localhost = "localhost";
    $username = "root";
    $password = ""; 
    $db_name = "contact_form";
    $conn = mysqli_connect($localhost, $username, $password, $db_name);

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $full_name = mysqli_real_escape_string($conn, $_POST["full_name"]);
    $phone_number = mysqli_real_escape_string($conn, $_POST["phone_number"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $message = mysqli_real_escape_string($conn, $_POST["message"]);
    $ip_address = getUserIP(); // Get actual IP address
    $timestamp = date("Y-m-d H:i:s");

    // Insert data into database
    $sql = "INSERT INTO contact_submission (full_name, phone_number, email, message, ip_address, timestamp) 
            VALUES ('$full_name', '$phone_number', '$email', '$message', '$ip_address', '$timestamp')";

    if (mysqli_query($conn, $sql)) {
        // Generate and send OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        if (sendEmailNotification($full_name, $phone_number, $email, $message, $otp)) {
            $_SESSION['msg'] = "OTP sent to your email!";
            header("Location: verify_otp.php");
        } else {
            $_SESSION['msg'] = "Failed to send OTP email.";
            header("Location: index.php");
        }
    } else {
        echo "Database error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
