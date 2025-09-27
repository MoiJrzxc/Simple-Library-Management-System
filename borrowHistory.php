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

    <?php if ($result && $result->num_rows > 0): ?>
    <table>
        <tr>
            <th>Book</th>
            <th>Borrower</th>
            <th>Borrowed At</th>
            <th>Returned At</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['borrower_name']) ?></td>
            <td><?= htmlspecialchars($row['borrowed_at']) ?></td>
            <td><?= htmlspecialchars($row['returned_at'] ?? 'Not Returned') ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p class="message">No borrow records yet.</p>
    <?php endif; ?>

    <form action="librarian.php">
        <button type="submit" class="back-button">⬅ Back to Catalog</button>
    </form>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
