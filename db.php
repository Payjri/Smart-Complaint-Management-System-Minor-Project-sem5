<?php
// Database connection
$host = "localhost";  // safer for XAMPP
$username = "root";   // default user
$password = "";       // default no password
$dbname = "smart_complaint_system";

// If your MySQL runs on default port, use 3306; otherwise, 3307
$conn = new mysqli($host, $username, $password, $dbname, 3307);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
