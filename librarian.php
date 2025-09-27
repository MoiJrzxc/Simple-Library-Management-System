<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Manager</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
        }
        form, table { 
            margin-bottom: 20px; 
        }
        table { 
            border-collapse: collapse; 
            width: 80%; 
        }
        th, td { 
            border: 1px solid #ccc; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background: #eee; 
        }
        input[type=text], 
        input[type=number] { 
            padding: 5px; 
            margin: 5px; 
        }
        button { 
            padding: 6px 10px; 
            cursor: pointer;
        }
    </style>
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
    <input type="text" name="search" placeholder="Search by title,or author">
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
    <th>Actions</th>
</tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['title']}</td>
            <td>{$row['author']}</td>
            <td>{$row['year']}</td>
            <td>
                <a href='update.php?id={$row['id']}'>Edit</a> | 
                <a href='delete.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No books found</td></tr>";
}

echo "</table>";
?>

</body>
</html>
