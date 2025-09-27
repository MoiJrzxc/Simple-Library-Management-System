<?php
session_start();
include "db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header("Location: index.php");
    exit();
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";
$sql = $search
    ? "SELECT * FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%'"
    : "SELECT * FROM books";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>Librarian Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h1>Library Catalog</h1>

<div class="logout">
<form method="post" action="logout.php">
    <button type="submit">Logout</button>
</form>
</div>

<h2>Add a New Book</h2>
<form method="POST" action="createBooks.php">
    <input type="text" name="title" placeholder="Title" required>
    <input type="text" name="author" placeholder="Author" required>
    <input type="number" name="year" placeholder="Year" required min="1000" max="2025">
    <button type="submit">Add Book</button>
</form>

<h2>Search</h2>
<form method="GET">
    <input type="text" name="search" placeholder="Search by title or author" value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="history">Search</button>
</form>

<table>
<tr><th>Title</th><th>Author</th><th>Year</th><th>Actions</th></tr>
<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()):
        $id = $row['id'];
?>
<tr>
<td><?= htmlspecialchars($row['title']) ?></td>
<td><?= htmlspecialchars($row['author']) ?></td>
<td><?= htmlspecialchars($row['year']) ?></td>
<td class="actions">
    <form method="post" action="deleteBooks.php" onsubmit="return confirm('Delete?');">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit" class="delete">Delete</button>
    </form>
    <form method="get" action="editBooks.php">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit" class="edit">Edit</button>
    </form>
    <form method="get" action="borrowHistory.php">
        <input type="hidden" name="book_id" value="<?= $id ?>">
        <button type="submit" class="history">History</button>
    </form>
</td>
</tr>
<?php
    endwhile;
} else echo "<tr><td colspan='4'>No books found</td></tr>";
$conn->close();
?>
</table>
</div>
</body>
</html>
