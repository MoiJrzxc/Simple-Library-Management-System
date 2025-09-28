<?php
include "db.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int)$_POST["id"];
    $title = $_POST["title"];
    $author = $_POST["author"];
    $year = (int)$_POST["year"];

    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, year=? WHERE id=?");
    $stmt->bind_param("ssii", $title, $author, $year, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: librarian.php");
    exit();
}

$book = null;
if (isset($_GET["id"])) {
    $id = (int)$_GET["id"];
    $book = $conn->query("SELECT * FROM books WHERE id=$id")->fetch_assoc();
    if (!$book) die("Book not found.");
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h1>Edit Book</h1>
<?php if ($book): ?>
<form method="post">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
    <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
    <input type="number" name="year" value="<?= htmlspecialchars($book['year']) ?>" required min="1000" max="2025">
    <button type="submit" class="edit">Save Changes</button>
    <a href="librarian.php"><button type="button" class="back-button">Cancel</button></a>
</form>
<?php endif; ?>
</div>
</body>
</html>
