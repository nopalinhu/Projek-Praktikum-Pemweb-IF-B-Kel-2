<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "assignment_tracker"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>