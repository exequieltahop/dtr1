<?php
// Step 1: Establish database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "attendance";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>