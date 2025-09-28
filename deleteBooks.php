<?php
header("Content-Type: application/json");

$conn = new mysqli("db", "root", "rootpassword", "testdb");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => $conn->connect_error]);
    exit;
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();

    if ($ok) {
        echo json_encode(["success" => true, "id" => $id]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "No ID provided"]);
}

$conn->close();
?>
