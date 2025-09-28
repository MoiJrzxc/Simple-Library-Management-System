<?php
include "db.php";
session_start();

// Get book ID from GET parameter (optional: show all if not set)
$book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;

if ($book_id > 0) {
    // History for a specific book
    $sql = "SELECT b.title, u.name AS student_name, br.borrowed_at, br.returned_at
            FROM borrows br
            JOIN books b ON br.book_id = b.id
            JOIN users u ON br.student_id = u.id
            WHERE b.id = $book_id
            ORDER BY br.borrowed_at DESC";
} else {
    // All history
    $sql = "SELECT b.title, u.name AS student_name, br.borrowed_at, br.returned_at
            FROM borrows br
            JOIN books b ON br.book_id = b.id
            JOIN users u ON br.student_id = u.id
            ORDER BY br.borrowed_at DESC";
}

$result = $conn->query($sql);
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
if ($result === false) {
    echo "<p style='color:red;'>SQL Error: " . htmlspecialchars($conn->error) . "</p>";
} elseif ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>";
    if ($book_id === 0) echo "<th>Book</th>";
    echo "  <th>Student</th>
            <th>Borrowed At</th>
            <th>Returned At</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        if ($book_id === 0) echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "  <td>" . htmlspecialchars($row['student_name']) . "</td>
                <td>" . $row['borrowed_at'] . "</td>
                <td>" . ($row['returned_at'] ?? 'Not Returned') . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No borrow records found.</p>";
}

$conn->close();
?>
