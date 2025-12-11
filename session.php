<?php
// session.php

// 1. ALWAYS START THE SESSION FIRST
if (!isset($_SESSION)) {
    session_start();
}

// 2. CHECK IF THE USER IS LOGGED IN
// Note: We use 'user_id' as set in the final login.php file
if (!isset($_SESSION['user_id'])) {
    // If user_id is NOT set, redirect them to the login page
    header("Location: login.php");
    exit();
}

// 3. IF LOGGED IN, DEFINE VARIABLES FOR CONVENIENCE
$uid = $_SESSION['user_id'];
$un = $_SESSION['user_name']; // Use 'user_name' as set in login.php

?>