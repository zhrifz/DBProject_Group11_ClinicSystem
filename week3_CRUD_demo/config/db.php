<?php
$host = "localhost";
$user = "root"; 
$pass = ""; 
$dbname = "clinic_db"; // atau nama database kamu sekarang

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
