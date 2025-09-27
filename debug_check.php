<?php
$conn = new mysqli("db", "root", "rootpassword", "testdb");
if ($conn->connect_error) {
    die("Conn fail: " . $conn->connect_error);
}
echo "Connected to DB: " . $conn->host_info . "<br>";
$res = $conn->query("SELECT DATABASE() AS db, USER() AS user");
$row = $res->fetch_assoc();
echo "Using DB: {$row['db']}, As user: {$row['user']}<br><hr>";

$res2 = $conn->query("SELECT id, title, author, year FROM books ORDER BY id DESC LIMIT 10");
echo "<pre>";
while ($r = $res2->fetch_assoc()) {
    print_r($r);
}
echo "</pre>";
?>
