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
    <title>Library Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        h2 {
            margin-top: 0;
            color: #444;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="number"] {
            padding: 10px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: calc(30% - 20px);
        }

        button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        .message {
            margin: 15px 0;
            font-weight: bold;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        table th {
            background: #007bff;
            color: white;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        table tr:hover {
            background: #f1f1f1;
        }

        .actions form {
            display: inline-block;
        }

        .actions button {
            background: #28a745;
        }

        .actions button:hover {
            background: #1e7e34;
        }

        .actions form:first-child button {
            background: #dc3545;
        }

        .actions form:first-child button:hover {
            background: #a71d2a;
        }
        
    </style>
</head>

<body>

    <h1>Library Manager</h1>

    <button onclick="window.location.href='index.php'">Back to Home</button>

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
    // Search filter
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