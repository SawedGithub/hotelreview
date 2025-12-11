<?php
// mysqli_connect.php

if (!defined('DB_USER')) {

    // You MUST replace 'hotelreview' with the EXACT name of your database.
    // Replace 'your_password' with your actual MySQL password (often blank in XAMPP/WAMP).
    
    DEFINE ('DB_USER', 'root');
    DEFINE ('DB_PASSWORD', ''); 
    DEFINE ('DB_HOST', 'localhost');
    DEFINE ('DB_NAME', 'hotelreview'); // <--- CHECK THIS IS YOUR DATABASE NAME

    // Make the connection:
    $dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );

    // Set the encoding
    mysqli_set_charset($dbc, 'utf8');
}
?>