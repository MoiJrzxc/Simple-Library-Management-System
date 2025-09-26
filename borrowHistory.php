<?php
$servername = "db";
$username = "root";
$password = "rootpassword";
$dbname = "testdb";

$conn = new mysqli(
    hostname: $servername,
    username: $username,
    password: $password,
    database: $dbname
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT b.title, br.student_name, br.borrowed_at, br.returned_at
                        FROM borrows br
                        JOIN books b ON br.book_id = b.id
                        ORDER BY br.borrowed_at DESC");

echo "<h1>Borrow History</h1>";
if ($result->num_rows > 0) {
    echo "<table border='1'><tr><th>Book</th><th>Student</th><th>Borrowed At</th><th>Returned At</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['title']) . "</td>
                <td>" . htmlspecialchars($row['student_name']) . "</td>
                <td>" . $row['borrowed_at'] . "</td>
                <td>" . ($row['returned_at'] ?? 'Not Returned') . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No borrow records yet.";
}
$conn->close();
?>
