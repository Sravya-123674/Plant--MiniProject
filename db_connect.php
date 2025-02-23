<?php
// Database connection settings
$host = "localhost";
$username = "root";
$password = ""; // Default XAMPP MySQL password is empty
$dbname = "plantcare";
$port = 3307; // Use port 3307 since XAMPP MySQL runs on this port

// Create a connection using MySQLi
$mysqli = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 

echo "Database connected successfully!";
?>
