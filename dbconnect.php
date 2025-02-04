<?php
$servername = "localhost";
$username = "root";
$password = ""; // Adjust as per your local database password
$dbname = "LibraryManagement";

// Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
