<?php
include "db.php";
session_start();

// Get book ID from GET parameter (optional: show all if not set)
$book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;

// Fetch borrows
if ($book_id > 0) {
    // Show history for a specific book
    $stmt = $conn->prepare("
        SELECT b.title, u.name AS borrower_name, br.borrowed_at, br.returned_at
        FROM borrows br
        JOIN books b ON br.book_id = b.id
        JOIN users u ON br.student_id = u.id
        WHERE b.id = ?
        ORDER BY br.borrowed_at DESC
    ");
    $stmt->bind_param("i", $book_id);
} else {
    // Show all borrows
    $stmt = $conn->prepare("
        SELECT b.title, u.name AS borrower_name, br.borrowed_at, br.returned_at
        FROM borrows br
        JOIN books b ON br.book_id = b.id
        JOIN users u ON br.student_id = u.id
        ORDER BY br.borrowed_at DESC
    ");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrow/Return History</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Borrow/Return History</h1>

<?php
if (isset($_GET['id'])) {
    $book_id = (int) $_GET['id'];

    // Get book info
    $book = $conn->query("SELECT title, author, year FROM books WHERE id=$book_id")->fetch_assoc();

    if ($book) {
        echo "<h2>" . htmlspecialchars($book['title']) . " by " . htmlspecialchars($book['author']) . " (" . $book['year'] . ")</h2>";

        // Fetch borrow/return history for this book
        $sql = "SELECT student_name, borrowed_at, returned_at 
                FROM borrows 
                WHERE book_id=$book_id 
                ORDER BY borrowed_at DESC";
    } else {
        echo "<p>Book not found.</p>";
        exit;
    }
} else {
    // If no book selected, show all history
    $sql = "SELECT b.title, br.student_name, br.borrowed_at, br.returned_at
            FROM borrows br
            JOIN books b ON br.book_id = b.id
            ORDER BY br.borrowed_at DESC";
}


$result = $conn->query($sql);
if ($result === false) {
    echo "<p style='color:red;'>SQL Error: " . htmlspecialchars($conn->error) . "</p>";
} else if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>";
    if (!isset($_GET['id'])) echo "<th>Book</th>";
    echo "  <th>Student</th>
            <th>Borrowed At</th>
            <th>Returned At</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        if (!isset($_GET['id'])) echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "  <td>" . htmlspecialchars($row['student_name']) . "</td>
                <td>" . $row['borrowed_at'] . "</td>
                <td>" . ($row['returned_at'] ?? 'Not Returned') . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No borrow records found for this book or in the system.</p>";
}

$conn->close();
?>
