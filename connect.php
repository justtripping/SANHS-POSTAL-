<?php
$host = "localhost";     // Database host
$user = "root";          // Database username
$password = "";          // Database password
$database = "attsystem";   // Database name

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>