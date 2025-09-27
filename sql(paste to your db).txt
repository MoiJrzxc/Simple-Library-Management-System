<?php
// Database connection
$servername = "db";
$username = "root";
$password = "rootpassword";
$dbname = "testdb";
$conn = new mysqli(hostname: $servername, username: $username, password: $password, database: $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Book Catalog</title>
</head>
<body>
  <div class="container">
    <h1>Book Catalog</h1>

    <h2>Add a New Book</h2>
    <form method="post" action="createBooks.php">
      <input type="text" name="title" placeholder="Book Title" required>
      <input type="text" name="author" placeholder="Author" required>
      <input type="number" name="year" placeholder="Year" required>
      <button type="submit">Add Book</button>
    </form>

    <?php
    // Handle success/error messages
    if (isset($_GET['success'])) {
        echo "<p class='message success'>Book added successfully!</p>";
    }
    if (isset($_GET['error'])) {
        echo "<p class='message error'>All fields are required.</p>";
    }

    // Fetch and display books
    $sql = "SELECT id, title, author, year FROM books";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      echo "<table>";
      echo "<tr><th>Title</th><th>Author</th><th>Year</th><th>Actions</th></tr>";
      while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["author"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["year"]) . "</td>";
        echo "<td class='actions'>
                <form method='post' action='deleteBooks.php' onsubmit=\"return confirm('Are you sure you want to delete this book?');\">
                  <input type='hidden' name='id' value='" . $row["id"] . "'>
                  <button type='submit'>Delete</button>
                </form>
                <form method='get' action='editBooks.php'>
                  <input type='hidden' name='id' value='" . $row["id"] . "'>
                  <button type='submit'>Edit</button>
                </form>
              </td>";
        echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "<p>No books available.</p>";
    }

    // Close connection
    $conn->close();
    ?>
  </div>
</body>
</html>
