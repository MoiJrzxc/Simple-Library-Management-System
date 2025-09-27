<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Library User</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form, table { margin-bottom: 20px; }
        table { border-collapse: collapse; width: 80%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #eee; }
        input[type=text], input[type=number] { padding: 5px; margin: 5px; }
        button { padding: 6px 10px; cursor: pointer; }
    </style>
</head>
<body>

<h1>Library User</h1>

<?php
if (isset($_GET['success'])) echo "<p style='color:green;'>Book borrowed successfully!</p>";
if (isset($_GET['error']) && $_GET['error'] === 'already_borrowed') 
    echo "<p style='color:red;'>This book is already borrowed.</p>";
?>

<h2>Search for a Book</h2>
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

echo "<table>
<tr>
    <th>Title</th>
    <th>Author</th>
    <th>Year</th>
    <th>Action</th>
</tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $book_id = $row['id'];

        // Check if this book is currently borrowed
        $borrow_check = $conn->query("SELECT id FROM borrows WHERE book_id=$book_id AND returned_at IS NULL");
        $isBorrowed = $borrow_check->num_rows > 0;

        echo "<tr>
            <td>" . htmlspecialchars($row['title']) . "</td>
            <td>" . htmlspecialchars($row['author']) . "</td>
            <td>" . htmlspecialchars($row['year']) . "</td>
            <td>";

        if (!$isBorrowed) {
            echo "<form method='post' action='borrowBooks.php' style='display:inline-block;'>
                    <input type='hidden' name='book_id' value='{$book_id}'>
                    <button type='submit'>Borrow</button>
                  </form>";
        } else {
            echo "<form method='post' action='returnBooks.php' style='display:inline-block;'>
                    <input type='hidden' name='book_id' value='{$book_id}'>
                    <button type='submit'>Return</button>
                  </form>";
        }

        echo "</td></tr>";
    }
} else {
    echo "<tr><td colspan='4'>No books found</td></tr>";
}
echo "</table>";

$conn->close();
?>
</body>
</html>
