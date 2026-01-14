<?php
$host = "localhost";
$db = "AMS";
$user = "root";
$password = "";

$conn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
try{
    $conn= new PDO ($conn, $user, $password);
    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    print("Connnection Sucessful");
}catch (PDOException $e){
    die("❌ Database connection failed: " . $e->getMessage());
}

?>