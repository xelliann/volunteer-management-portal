<?php
$host = "localhost";
$dbname = "volunteer_portal";
<<<<<<< HEAD
$username = "root";  // Default XAMPP user
$password = "";      // Default XAMPP has no password
=======
$username = "root";
$password = "";
>>>>>>> 8a3025390cec50e494310adbfb983a08a0684839

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
<<<<<<< HEAD
?>
=======
>>>>>>> 8a3025390cec50e494310adbfb983a08a0684839
