<?php
// Set the timezone to Asia/Manila
date_default_timezone_set("Asia/Manila");

// Get the current date and time in a specific format
$date = date('F j, Y g:i:a');
$sqldate = date('Y-m-d h:i');
$current_date = date('Y-m-d');



// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$database = "web_inventory";

// Establish a database connection
$conn = mysqli_connect($host, $username, $password, $database);



// $to = "SELECT @@global.time_zone, @@session.time_zone;";
// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    echo 'updating....';
}
