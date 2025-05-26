<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "auction_db";

// Create connection
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
