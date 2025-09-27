<?php
// Handle role selection
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $role = $_POST['role'];

    if ($role === "user") {
        header("Location: user.php");
        exit();
    } elseif ($role === "librarian") {
        header("Location: librarian.php");
        exit();
    }
}
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
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
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
</head>
<body>
    <div class="container">
        <h1>📚 Welcome to the Library System</h1>

        <form method="POST" action="">
            <h3>Select Role:</h3>

            <label>
                <input type="radio" name="role" value="user" required> User
            </label>
            <label>
                <input type="radio" name="role" value="librarian" required> Librarian
            </label>

            <button type="submit">Enter</button>
        </form>

        <div class="footer">
            © <?= date("Y") ?> Library Management System
        </div>
    </div>
</body>
</html>
