<?php
// config.php
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password is empty in XAMPP
$dbname = "student"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
