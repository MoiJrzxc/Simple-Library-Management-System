<?php
include "db.php";

//Check if form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = (int)$_POST['year'];

    $stmt = $conn->prepare("INSERT INTO books (title, author, year) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $author, $year);
    $stmt->execute();
    $stmt->close();
    header("Location: librarian.php");
    exit();
}
$conn->close();
?>
