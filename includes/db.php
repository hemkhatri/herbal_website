<?php
$host = 'localhost';
$db   = 'aushadhi_db';
$user = 'root';
$pass = ''; // Default for XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>


