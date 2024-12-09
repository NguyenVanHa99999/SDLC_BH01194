<?php
$host = 'localhost';
$username = 'root';
$password = ''; // Mặc định là rỗng với XAMPP
$db_name = 'StudentManagement';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>