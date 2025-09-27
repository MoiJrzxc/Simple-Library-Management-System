<?php include "db.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library User</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f6f9; 
            margin: 0; 
            padding: 0;
        }

        .container {
            width: 85%;
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        h2 {
            color: #444;
            margin-top: 20px;
        }

        form {
            margin: 15px 0;
        }

        input[type=text], input[type=number] { 
            padding: 10px; 
            margin: 5px;
            width: 250px;
            border: 1px solid #ccc; 
            border-radius: 5px; 
        }

        button { 
            padding: 10px 18px; 
            margin: 5px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        button:hover {
            opacity: 0.9;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .message {
            margin: 10px 0;
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

        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: center; 
        }

        th { 
            background: #007bff; 
            color: white; 
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .actions form {
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>📚 Library User</h1>

    <form action="index.php" method="get">
        <button type="submit" class="btn-secondary">⬅ Back to Role Selection</button>
    </form>

    <?php
    if (isset($_GET['success'])) {
        echo "<p class='message success'>✅ Book borrowed successfully!</p>";
    }
    if (isset($_GET['error']) && $_GET['error'] === 'already_borrowed') {
        echo "<p class='message error'>⚠️ This book is already borrowed.</p>";
    }
    ?>

    <h2>🔍 Search for a Book</h2>
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by title or author">
        <button type="submit" class="btn-primary">Search</button>
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
                <td class='actions'>";

            if (!$isBorrowed) {
                                echo "<form method='post' action='borrowBooks.php' style='display:inline-block;'>
                                                <input type='hidden' name='book_id' value='{$book_id}'>
                                                <input type='text' name='student_name' placeholder='Your Name' required style='width:120px; margin-right:5px;'>
                                                <button type='submit' class='btn-success'>Borrow</button>
                                            </form>";
            } else {
                echo "<form method='post' action='returnBooks.php'>
                        <input type='hidden' name='book_id' value='{$book_id}'>
                        <button type='submit' class='btn-danger'>Return</button>
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
</div>
</body>
</html>
