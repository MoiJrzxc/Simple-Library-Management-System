<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Library Manager</h1>

<h2>Add a New Book</h2>
<form action="createBooks.php" method="POST">
    <input type="text" name="title" placeholder="Title" required>
    <input type="text" name="author" placeholder="Author" required>
    <input type="number" name="year" placeholder="Year" required min="1000" max="2025">
    <button type="submit">Add Book</button>
</form>

<h2>Library Catalog</h2>
<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by title or author">
    <button type="submit">Search</button>
</form>

<?php
$search = "";
if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM books";
}

$result = $conn->query($sql);

echo "<table border='1'>
<tr>
    <th>Title</th>
    <th>Author</th>
    <th>Year</th>
    <th>Actions</th>
</tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . htmlspecialchars($row['title']) . "</td>
            <td>" . htmlspecialchars($row['author']) . "</td>
            <td>" . htmlspecialchars($row['year']) . "</td>
            <td>
                <a href='editBooks.php?id=" . $row['id'] . "'>Edit</a> | 
                <a href='deleteBooks.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a> | 
                <a href='borrowHistory.php?id=" . $row['id'] . "'>History</a>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No books found</td></tr>";
}

echo "</table>";

$conn->close();
?>

</body>
</html>
