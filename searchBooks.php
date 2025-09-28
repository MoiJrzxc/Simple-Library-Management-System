<?php
include "db.php";

$search = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $search = trim($_POST["search"]);
  $sql = "SELECT title, author, year FROM books WHERE deleted=0 AND (title LIKE ? OR author LIKE ?)";
  $stmt = $conn->prepare($sql);
  $likeSearch = "%" . $search . "%";
  $stmt->bind_param("ss", $likeSearch, $likeSearch);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  $result = false;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Books</title>
</head>
<body>
  <h1>Search Books</h1>
  <form method="post" action="searchBooks.php">
    <input type="text" name="search" placeholder="Enter title or author" value="<?php echo htmlspecialchars($search); ?>" required>
    <button type="submit">Search</button>
  </form>

  <?php
  if ($result !== false) {
    if ($result->num_rows > 0) {
      echo "<table border='1'><tr><th>Title</th><th>Author</th><th>Year</th></tr>";
      while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row["title"]) . "</td><td>" . htmlspecialchars($row["author"]) . "</td><td>" . htmlspecialchars($row["year"]) . "</td></tr>";
      }
      echo "</table>";
    } else {
      echo "No books found.";
    }
  }
  ?>
</body>
</html>

<?php $conn->close(); ?>
