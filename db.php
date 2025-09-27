<?php
$servername = "db"; // or "localhost" if outside Docker
$username = "root";
$password = "rootpassword";
$dbname = "testdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
