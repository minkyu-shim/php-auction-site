<?php
require_once __DIR__ . '/config.php';

$dbHost = getenv('DB_HOST') ?: "localhost";
$dbUser = getenv('DB_USER') ?: "root";
$dbPass = getenv('DB_PASS');
$dbPass = $dbPass === false ? "" : $dbPass;
$dbName = getenv('DB_NAME') ?: "auction_db";
$dbPort = (int) (getenv('DB_PORT') ?: 3306);

// Create connection
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName, $dbPort);

// Check connection
if (!$conn) {
    if ($debug) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    http_response_code(500);
    exit("Database connection failed.");
}

mysqli_set_charset($conn, 'utf8mb4');
