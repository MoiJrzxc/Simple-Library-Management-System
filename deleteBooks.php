<?php
header("Content-Type: application/json");

include "db.php";

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("UPDATE books SET deleted=1 WHERE id=?");
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
