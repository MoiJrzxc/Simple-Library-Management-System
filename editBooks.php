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
    <h1>Edit Book</h1>
    <form method="post" action="edit_book.php">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        Title: <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>"><br>
        Author: <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>"><br>
        Year: <input type="number" name="year" value="<?php echo htmlspecialchars($book['year']); ?>"><br>
        <button type="submit">Save Changes</button>
    </form>
    <?php
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = (int) $_POST["id"];
    $title = $_POST["title"];
    $author = $_POST["author"];
    $year = $_POST["year"];

    $conn->query("UPDATE books SET title='$title', author='$author', year=$year WHERE id=$id");

    header("Location: library.php");
    exit;
}
?>
