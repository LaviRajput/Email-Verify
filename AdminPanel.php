<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .verified {
            color: green;
            font-weight: bold;
        }
        .unverified {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Admin Panel</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>IP Address</th>
                <th>Verification Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Database connection
            $conn = new mysqli('localhost', 'root', '', 'contact_form');

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Correct SQL query
            $sql = "SELECT full_name, email, ip_address, verification_status FROM contact_submission";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data for each row
                while ($row = $result->fetch_assoc()) {
                    // Properly check for "verified" or "unverified" status
                    $status_class = $row['verification_status'] === 'verified' ? 'verified' : 'unverified';
                    $status_text = ucfirst($row['verification_status']); // Capitalize first letter
                    echo "<tr>
                        <td>" . htmlspecialchars($row['full_name']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['ip_address']) . "</td>
                        <td class='$status_class'>$status_text</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No records found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>
