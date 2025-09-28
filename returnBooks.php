<?php
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = (int) $_POST['book_id'];

    // Return book by_updating returned_at
    $sql = "UPDATE borrows SET returned_at=NOW() WHERE book_id=$book_id AND returned_at IS NULL LIMIT 1";

    if ($conn->query($sql) === TRUE) {
        header("Location: user.php?success=returned");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
