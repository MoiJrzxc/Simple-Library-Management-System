<?php
$servername = "db"; // or "localhost"
$username   = "root";
$password   = "rootpassword";
$dbname     = "library_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        role ENUM('librarian','user') NOT NULL
    )
");

$conn->query("
    CREATE TABLE IF NOT EXISTS books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(100),
        year INT
    )
");

$conn->query("
    CREATE TABLE IF NOT EXISTS borrows (
        id INT AUTO_INCREMENT PRIMARY KEY,
        book_id INT NOT NULL,
        student_id INT NOT NULL,
        borrowed_at DATETIME NOT NULL,
        returned_at DATETIME NULL,
        FOREIGN KEY (book_id) REFERENCES books(id),
        FOREIGN KEY (student_id) REFERENCES users(id)
    )
");

// --- INSERT SAMPLE DATA ONLY IF TABLES ARE EMPTY ---
$users_empty = $conn->query("SELECT COUNT(*) AS cnt FROM users")->fetch_assoc()["cnt"] == 0;
$books_empty = $conn->query("SELECT COUNT(*) AS cnt FROM books")->fetch_assoc()["cnt"] == 0;
$borrows_empty = $conn->query("SELECT COUNT(*) AS cnt FROM borrows")->fetch_assoc()["cnt"] == 0;

if ($users_empty) {
    $conn->query("INSERT INTO users (name, role) VALUES
    ('Moises', 'librarian'),
    ('Angel', 'user'),
    ('Jorick', 'user'),
    ('Petter', 'user'),
    ('Angericksesmielter', 'user')");
}

if ($books_empty) {
    $conn->query("INSERT INTO books (title, author, year) VALUES
    ('Harry Potter and the Sorcerer''s Stone', 'J.K. Rowling', 1998),
    ('The Hobbit', 'J.R.R. Tolkien', 1937),
    ('To Kill a Mockingbird', 'Harper Lee', 1960),
    ('1984', 'George Orwell', 1949),
    ('Pride and Prejudice', 'Jane Austen', 1813),
    ('Test Book 1', 'Author A', 2020),
    ('Test Book 2', 'Author B', 2021)");
}

if ($borrows_empty) {
    $conn->query("INSERT INTO borrows (book_id, student_id, borrowed_at, returned_at) VALUES
    (1, 1, '2025-09-01 10:00:00', '2025-09-05 12:00:00'),
    (1, 3, '2025-09-10 09:30:00', NULL),
    (2, 4, '2025-09-02 11:00:00', '2025-09-06 14:00:00'),
    (3, 1, '2025-09-03 13:00:00', '2025-09-07 16:00:00'),
    (3, 5, '2025-09-08 15:00:00', NULL), 
    (4, 3, '2025-09-04 14:00:00', NULL),
    (5, 4, '2025-09-05 16:00:00', '2025-09-10 10:00:00'),
    (6, 1, '2025-09-06 09:00:00', '2025-09-10 12:00:00'),
    (6, 4, '2025-09-12 10:30:00', NULL),
    (7, 3, '2025-09-07 11:00:00', NULL)");
}

?>
