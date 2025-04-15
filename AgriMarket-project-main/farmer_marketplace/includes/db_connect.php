<?php
// Database configuration
$host = 'localhost';
$dbname = 'farmer_marketplace';
$username = 'root';
$password = '';

try {
    // Create PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // If there is an error with the connection, stop the script and display the error
    die("Connection failed: " . $e->getMessage());
}
?>