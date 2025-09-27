<?php
session_start();
include "db.php";

// Fetch all users
$users = [];
$result = $conn->query("SELECT id, name, role FROM users ORDER BY role, name");
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = (int)$_POST['user_id'];

    // Get user info
    $stmt = $conn->prepare("SELECT name, role FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name, $role);
    $stmt->fetch();
    $stmt->close();

    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $name;
    $_SESSION['role'] = $role;

    if ($role === "user") header("Location: user.php");
    else header("Location: librarian.php");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
    <h1>Welcome to the Library</h1>
    <form method="POST">
        <label>Select Your Name:</label>
        <select name="user_id" required>
            <option value="">-- Choose User --</option>
            <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>">
                    <?= htmlspecialchars($u['name']) ?> (<?= $u['role'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Enter</button>
    </form>
</div>
</body>
</html>
