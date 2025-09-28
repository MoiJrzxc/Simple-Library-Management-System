<?php
session_start();
include "db.php";

$current_user_id = $_SESSION['user_id'] ?? 1;

// Borrow a book
if (isset($_POST['borrowBook'], $_POST['book_id'])) {
    $book_id = (int)$_POST['book_id'];

    $check = $conn->query("SELECT * FROM borrows WHERE book_id=$book_id AND returned_at IS NULL");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO borrows (book_id, student_id, borrowed_at) VALUES ($book_id, $current_user_id, NOW())");
        header("Location: user.php?success=borrowed");
        exit();
    } else {
        header("Location: user.php?error=already_borrowed");
        exit();
    }
}

// Return a book
if (isset($_POST['returnBook'], $_POST['book_id'])) {
    $book_id = (int)$_POST['book_id'];
    $conn->query("UPDATE borrows SET returned_at=NOW() WHERE book_id=$book_id AND student_id=$current_user_id AND returned_at IS NULL LIMIT 1");
    header("Location: user.php?success=returned");
    exit();
}

// Fetch books
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";
$sql = $search
    ? "SELECT * FROM books WHERE deleted=0 AND (title LIKE '%$search%' OR author LIKE '%$search%')"
    : "SELECT * FROM books WHERE deleted=0";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>iGit</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="igit.png">
</head>
<body>

<!-- Nav Bar -->
<nav class="navbar">
    <div class="nav-left">
        <img src="igit.png" alt="iGit Logo" class="nav-logo">
        <span class="nav-title">iGit Library User</span>
    </div>
    <div class="nav-right">
        <a href="logout.php" class="nav-link">Log out</a>
    </div>
</nav>

<div class="container">

<?php
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'borrowed') echo "<p class='message success'>Book borrowed successfully!</p>";
    if ($_GET['success'] === 'returned') echo "<p class='message success'>Book returned successfully!</p>";
}
if (isset($_GET['error']) && $_GET['error'] === 'already_borrowed')
    echo "<p class='message error'>This book is already borrowed.</p>";
?>

<h2>Library Catalog</h2>
<form method="GET">
    <input type="text" name="search" placeholder="Search by title or author" value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="history">Search</button>
</form>

<table>
<tr><th>Title</th><th>Author</th><th>Year</th><th>Action</th></tr>
<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()):
        $book_id = $row['id'];

        $borrow_check = $conn->query("SELECT student_id FROM borrows WHERE book_id=$book_id AND returned_at IS NULL LIMIT 1");
        $isBorrowed = $borrow_check->num_rows > 0;
        $borrowed_by = $isBorrowed ? $borrow_check->fetch_assoc()['student_id'] : null;
?>
<tr>
<td><?= htmlspecialchars($row['title']) ?></td>
<td><?= htmlspecialchars($row['author']) ?></td>
<td><?= htmlspecialchars($row['year']) ?></td>
<td>
    <form method="post">
        <input type="hidden" name="book_id" value="<?= $book_id ?>">
        <?php if ($isBorrowed && $borrowed_by == $current_user_id): ?>
            <button type="submit" name="returnBook" class="return">Return</button>
        <?php elseif ($isBorrowed): ?>
            <button type="button" class="borrow" disabled>Unavailable</button>
        <?php else: ?>
            <button type="submit" name="borrowBook" class="borrow">Borrow</button>
        <?php endif; ?>
    </form>
</td>
</tr>
<?php
    endwhile;
} else {
    echo "<tr><td colspan='4'>No books found</td></tr>";
}
$conn->close();
?>
</table>
</div>
</body>
</html>
