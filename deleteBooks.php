<?php
$conn = new mysqli("db", "root", "rootpassword", "testdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id'])) {
    $id = (int) $_POST['id']; // cast to int, keeps it clean
    $conn->query("DELETE FROM books WHERE id=$id");
}

header("Location: library.php");
exit;
?>
