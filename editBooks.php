<?php
$conn = new mysqli("db", "root", "rootpassword", "library_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    $id = (int) $_GET["id"];
    $stmt = $conn->prepare("SELECT title, author, year FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $book = $stmt->get_result()->fetch_assoc();

    if (!$book) {
        echo "Book not found.";
        exit;
    }
    ?>
    <form id="editForm">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label>Title: 
            <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
        </label><br>
        <label>Author: 
            <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
        </label><br>
        <label>Year: 
            <input type="number" name="year" value="<?php echo htmlspecialchars($book['year']); ?>" required>
        </label><br>
        <button type="submit">Save Changes</button>
    </form>
    <?php
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    header("Content-Type: application/json");

    $id = (int) $_POST["id"];
    $title = trim($_POST["title"] ?? "");
    $author = trim($_POST["author"] ?? "");
    $year = (int) ($_POST["year"] ?? 0);

    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, year=? WHERE id=?");
    $stmt->bind_param("ssii", $title, $author, $year, $id);
    $ok = $stmt->execute();

    if ($ok) {
        echo json_encode([
            "success" => true,
            "id" => $id,
            "title" => $title,
            "author" => $author,
            "year" => $year
        ]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
