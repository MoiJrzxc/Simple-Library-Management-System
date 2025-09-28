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
    $user_id = (int) $_POST['user_id'];

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

    if ($role === "user")
        header("Location: user.php");
    else
        header("Location: librarian.php");
    exit();
}

$conn->close();
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
    <div class="login-container">
        <div class="login-header">
            <img src="igit.png" alt="Library Logo" class="login-logo">
            <h1>Welcome to iGit Library</h1>
        </div>

        <form method="POST">
            <label for="user_id">A Simple Library Management System</label>
            <select name="user_id" id="user_id" required>
                <option value="">Choose User</option>
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
