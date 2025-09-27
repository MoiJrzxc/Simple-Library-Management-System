<?php
$servername = "db";
$username = "root";
$password = "rootpassword";
$dbname = "testdb";
$conn = new mysqli(hostname: $servername, username: $username, password: $password, database: $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST["title"];
  $author = $_POST["author"];
  $year = $_POST["year"];

  if (!empty($title) && !empty($author) && !empty($year)) {
    $stmt = $conn->prepare("INSERT INTO books (title, author, year) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $author, $year);
    $stmt->execute();
    $stmt->close();
    // Redirect back to catalog after adding
    header("Location: librarian.php?success=1");
    exit();
  } else {
    // Redirect with error
    header("Location: librarian.php?error=1");
    exit();
  }
}

$conn->close();
?>