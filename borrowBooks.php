<?php
$servername = "db";
$username = "root";
$password = "rootpassword";
$dbname = "testdb";

$conn = new mysqli(
    hostname: $servername,
    username: $username,
    password: $password,
    database: $dbname
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['book_id'], $_POST['student_name'])) {
    $book_id = (int) $_POST['book_id'];
    $student_name = $conn->real_escape_string($_POST['student_name']);

    // Check if already borrowed
    $check = $conn->query("SELECT id FROM borrows WHERE book_id=$book_id AND returned_at IS NULL");
    if ($check->num_rows > 0) {
        echo "This book is already borrowed!";
        exit;
    }

    $sql = "INSERT INTO borrows (book_id, student_name) VALUES ($book_id, '$student_name')";
    if ($conn->query($sql) === TRUE) {
        header("Location: library.php?success=borrowed");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
$conn->close();
?>
