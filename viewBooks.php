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

// Fetch and display books
$sql = "SELECT title, author, year FROM books";
$result = $conn->query($sql);


// Display results in a table
if ($result->num_rows > 0) {
  echo "<h1>Book Catalog</h1>";
  echo "<table border='1'><tr><th>Title</th><th>Author</th><th>Year</th></tr>";
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>" . htmlspecialchars($row["title"]) . "</td><td>" . htmlspecialchars($row["author"]) . "</td><td>" . htmlspecialchars($row["year"]) . "</td></tr>";
  }
  echo "</table>";
} else {
  echo "No books available.";
}

// Close connection
$conn->close();
?>