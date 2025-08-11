<?php
$host = "localhost";
$dbname = "volunteer_portal";
$username = "root";  // Default XAMPP user
$password = "";      // Default XAMPP has no password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
