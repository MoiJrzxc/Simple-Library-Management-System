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
    <link rel="stylesheet" href="style.css">
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
