<?php
// This feature allows students to browse and view the catalog of available books.
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

// --- Create Book Form ---
echo "<h1>Book Catalog</h1>";
echo "<h2>Add a New Book</h2>";
echo "<form method='post' action='createBooks.php'>
        Title: <input type='text' name='title' required>
        Author: <input type='text' name='author' required>
        Year: <input type='number' name='year' required>
        <button type='submit'>Add Book</button>
      </form><br>";

// Handle success/error messages
if (isset($_GET['success'])) {
    echo "<p style='color:green;'>Book added successfully!</p>";
}
if (isset($_GET['error'])) {
    echo "<p style='color:red;'>All fields are required.</p>";
}

// Fetch and display books
$sql = "SELECT id, title, author, year FROM books";
$result = $conn->query($sql);

// Display results in a table
if ($result->num_rows > 0) {
  echo "<table border='1'><tr><th>Title</th><th>Author</th><th>Year</th><th>Actions</th></tr>";
  while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["author"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["year"]) . "</td>";
    echo "<td>
            <form method='post' action='deleteBooks.php' style='display:inline;' 
                onsubmit=\"return confirm('Are you sure you want to delete this book?');\">
                <input type='hidden' name='id' value='" . $row["id"] . "'>
                <button type='submit'>Delete</button>
            </form>
            <form method='get' action='editBooks.php' style='display:inline;'>
                <input type='hidden' name='id' value='" . $row["id"] . "'>
                <button type='submit'>Edit</button>
            </form>
          </td>";
    echo "</tr>";
  }
  echo "</table>";
} else {
  echo "No books available.";
}

// Close connection
$conn->close();
?>
