<?php
// Database connection variables
$dbhost = "localhost";
$dbuser = "root";         // Username (root for local development)
$dbpassword = "";         // Password (empty if no password for local setup)
$dbname = "tuneify";      // Your database name

// MySQLi connection (optional, since you're using PDO)
$conn = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

try {
    // Create a new PDO instance and assign it to $pdo
    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If there's a connection error, display a message and stop the script
    die('Database connection failed: ' . $e->getMessage());
}
?>