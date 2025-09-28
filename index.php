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
    <title>Library Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 85%;
            max-width: 500px;
            margin: 80px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 25px;
        }

        h3 {
            margin-bottom: 15px;
            color: #444;
        }

        label {
            display: block;
            margin: 12px 0;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="radio"] {
            margin-right: 8px;
            transform: scale(1.2);
        }

        button {
            padding: 12px 25px;
            margin-top: 20px;
            border: none;
            border-radius: 6px;
            background: #007bff;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        .footer {
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }
    </style>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>📚 Welcome to the Library System</h1>
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

        <div class="footer">
            © <?= date("Y") ?> Library Management System
        </div>
    </div>
</body>

</html>