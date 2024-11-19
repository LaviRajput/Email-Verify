<?php
// config.php

// Database configuration
$host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "contact_form";

// Create a connection
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection error: " . $conn->connect_error, 3, __DIR__ . "/error_log.txt");

    // Display a user-friendly message
    die("We are experiencing technical issues. Please try again later.");
}

function incrementVisitorCount($conn) {
    $sql = "INSERT INTO site_statistics (id, visitors) VALUES (1, 1)
            ON DUPLICATE KEY UPDATE visitors = visitors + 1";
    $conn->query($sql);
}

// Call the function to increment visitors
incrementVisitorCount($conn);

?>
