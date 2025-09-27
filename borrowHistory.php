<?php
include "db.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Borrow/Return History</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

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

<p><a href="librarian.php">⬅ Back to Catalog</a></p>

</body>
</html>
