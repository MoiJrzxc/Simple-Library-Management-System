<?php
$conn = new mysqli("db", "root", "rootpassword", "testdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['book_id'])) {
    $book_id = (int) $_POST['book_id'];

    // Mark latest borrow as returned
    $sql = "UPDATE borrows 
            SET returned_at = NOW() 
            WHERE book_id=$book_id AND returned_at IS NULL
            LIMIT 1";

    if ($conn->query($sql) === TRUE) {
        header("Location: library.php?success=returned");
    } else {
        echo "Error: " . $conn->error;
    }
}
$conn->close();
?>
