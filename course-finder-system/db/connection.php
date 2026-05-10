<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "course_finder";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection (secure version)
if ($conn->connect_error) {
    // Do not expose system details in production
    die("Database connection failed. Please contact the administrator.");
}

// Set charset for proper encoding (supports full Unicode / emojis)
$conn->set_charset("utf8mb4");

/*
SECURITY NOTES:
- Centralized database connection file for all modules
- utf8mb4 ensures full character support (including emojis)
- Error message is hidden to prevent system exposure
- This file is included in all system pages for consistency
*/
?>