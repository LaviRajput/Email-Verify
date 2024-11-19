<?php
// Include the config.php file for database connection
include('config.php');

// Query to get the total number of visitors
$sql = "SELECT visitors FROM site_statistics WHERE id = 1";
$result = $conn->query($sql);

// Fetch the visitor count
$visitor_count = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $visitor_count = $row['visitors'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="message" style="color:green;font-size:1rem;">
        <?php
           session_start();
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
        ?>
    </div>
    
    <h1>Contact Form</h1>
    <!-- Display total number of visitors -->
    <div class="visitor-count">
        <h2 style="color:green">Total number of visitors: <?php echo $visitor_count; ?></h2>
    </div>

    <div class="form-container">
        <form action="process_form.php" method="post">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required maxlength="100">

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required maxlength="20">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required maxlength="100">

            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
