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
<html>
<head>
    <title>Library Login</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
        }
        form, table { 
            margin-bottom: 20px; 
        }
        table { 
            border-collapse: collapse; 
            width: 80%; 
        }
        th, td { 
            border: 1px solid #ccc; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background: #eee; 
        }
        input[type=text], 
        input[type=number] { 
            padding: 5px; 
            margin: 5px; 
        }
        button { 
            padding: 6px 10px; 
            cursor: pointer;
        }
    </style>
</head>
<body>

<h1>Welcome to the Library System</h1>

<form method="POST" action="">
    <h3>Select Role:</h3>
    <label>
        <input type="radio" name="role" value="user" required> User
    </label>
    <br>
    <label>
        <input type="radio" name="role" value="librarian" required> Librarian
    </label>
    <br><br>

    <button type="submit">Enter</button>
</form>

</body>
</html>
