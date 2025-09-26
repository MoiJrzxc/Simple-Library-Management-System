<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Library User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Library User</h1>

<h2>Search for a Book</h2>
<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by title, author, or ISBN">
    <button type="submit">Search</button>
</form>

<?php
// Search filter
$search = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM books";
}

$result = $conn->query($sql);

echo "<table>
<tr>
    <th>Title</th>
    <th>Author</th>
    <th>Year</th>
</tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['title']}</td>
            <td>{$row['author']}</td>
            <td>{$row['year']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No books found</td></tr>";
}

echo "</table>";
?>

</body>
</html>
