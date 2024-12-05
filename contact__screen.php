<?php
// Database connection details
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = "";     // Default password for XAMPP is empty
$dbname = "student"; // Replace with your actual database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname ,3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data from the contact table
$sql = "SELECT * FROM contact";
$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact View</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Contact Table</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Number</th>
            <th>Role</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["Name"] . "</td>
                        <td>" . $row["Number"] . "</td>
                        <td>" . $row["Role"] . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4' style='text-align: center;'>No records found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>