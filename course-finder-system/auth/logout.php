<?php
session_start();

// Clear all session data
$_SESSION = array();

// Destroy session completely
session_destroy();

// Redirect to login page (correct path)
header("Location: ../auth/login.php");
exit();
?>