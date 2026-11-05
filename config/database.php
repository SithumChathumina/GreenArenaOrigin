<?php
//this is just the localhost databse file, just to run our local PCs
// Database connection settings.
// XAMPP uses user "root" with an empty password by default.
// Change these four values when you upload the site to a hosting account.
$host = 'localhost';
$database = 'sportshub3_db';
$user = 'root';
$password = '';

// Connect to MySQL using mysqli
mysqli_report(MYSQLI_REPORT_OFF);
$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($connection, 'utf8mb4');
