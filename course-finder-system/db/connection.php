<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "course_finder";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional: set charset (important for special characters)
$conn->set_charset("utf8");

// If needed for debugging (remove in final deployment)
// echo "Connected successfully";
?>