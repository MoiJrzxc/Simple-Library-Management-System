<?php
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = (int) $_POST['book_id'];

    // Check if the book is already borrowed
    $check = $conn->query("SELECT id FROM borrows WHERE book_id=$book_id AND returned_at IS NULL");
    if ($check->num_rows > 0) {
        header("Location: user.php?error=already_borrowed");
        exit;
    }

    $student_name = "Guest User"; // Default name
    $sql = "INSERT INTO borrows (book_id, student_name) VALUES ($book_id, '$student_name')";

    if ($conn->query($sql) === TRUE) {
        header("Location: user.php?success=borrowed");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
