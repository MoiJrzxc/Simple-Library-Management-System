<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Library User</title>
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
    $sql = "SELECT * FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR isbn LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM books";
}

$result = $conn->query($sql);

echo "<table>
<tr>
    <th>Title</th>
    <th>Author</th>
    <th>Year</th>
    <th>ISBN</th>
</tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['title']}</td>
            <td>{$row['author']}</td>
            <td>{$row['year']}</td>
            <td>{$row['isbn']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No books found</td></tr>";
}

echo "</table>";
?>

</body>
</html>
