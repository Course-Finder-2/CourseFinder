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

// Set charset for proper encoding (important for UTF-8 text)
$conn->set_charset("utf8mb4");

/*
NOTE:
- utf8mb4 is better than utf8 because it supports full Unicode (emojis, special characters)
- Remove any debug echo in final submission
*/

// Connection ready for use in all modules
?>