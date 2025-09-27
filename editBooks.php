<?php
$conn = new mysqli("db", "root", "rootpassword", "testdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    $id = (int) $_GET["id"];
    $result = $conn->query("SELECT title, author, year FROM books WHERE id=$id");
    $book = $result->fetch_assoc();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Edit Book</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f4f6f9;
                margin: 0;
                padding: 0;
            }

            .container {
                width: 85%;
                max-width: 600px;
                margin: 60px auto;
                background: #fff;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            }

            h1 {
                text-align: center;
                margin-bottom: 25px;
                color: #333;
            }

            label {
                display: block;
                margin: 12px 0 5px;
                font-weight: bold;
                color: #444;
            }

            input[type="text"],
            input[type="number"] {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 6px;
                margin-bottom: 15px;
            }

            button {
                padding: 12px 20px;
                border: none;
                border-radius: 6px;
                background: #007bff;
                color: white;
                font-weight: bold;
                cursor: pointer;
                transition: 0.3s;
                width: 100%;
            }

            button:hover {
                background: #0056b3;
            }

            .back-link {
                display: inline-block;
                margin-top: 15px;
                text-decoration: none;
                color: #007bff;
                font-size: 14px;
            }

            .back-link:hover {
                text-decoration: underline;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h1>✏️ Edit Book</h1>
            <form method="post" action="editBooks.php">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>

                <label for="author">Author:</label>
                <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>"
                    required>

                <label for="year">Year:</label>
                <input type="number" id="year" name="year" value="<?php echo htmlspecialchars($book['year']); ?>" required>

                <button type="submit">💾 Save Changes</button>


            </form>

            <a href="librarian.php" class="back-link">⬅ Back to Library</a>
        </div>
    </body>

    </html>
    <?php
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = (int) $_POST["id"];
    $title = $_POST["title"];
    $author = $_POST["author"];
    $year = $_POST["year"];

    $conn->query("UPDATE books SET title='$title', author='$author', year=$year WHERE id=$id");

    header("Location: librarian.php?success=updated");
    exit;
}
?>