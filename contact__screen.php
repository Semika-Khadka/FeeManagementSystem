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
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Laila:wght@300;400;500;600;700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/contact.css?v=1"> 
 
</head>
<body>
<div class="navbar">
<h1>Contact Table</h1>
    </div>

    <!-- Sidebar -->
    <div class="container">
        <div class="sidebar">
            <div class="logo"><a href="./welcome.php">FeeTrack</a></div>
            <div class="nav-links">
                <a href="./welcome.php">Dashboard</a>
                <a href="./profile_screen.php">Profile</a>
                <a href="./contact__screen.php">Contact</a>
                <!-- <form action="logout.php">
                    <input type="Submit" value="Logout">
                </form> -->
            </div>
        </div>
    
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