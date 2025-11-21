<?php
// Initialize the session
session_start();

// Store the username in a variable
$username = $_SESSION['username'];

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page with logout message and username
header("location: login.php?logout=1&username=$username");
exit;
?>
