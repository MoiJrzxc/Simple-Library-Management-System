<?php
session_start();
include "db.php";

// Librarian-only access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Manager</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
        }
        .modal-content {
            background: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
        }
        .close-btn {
            float: right;
            cursor: pointer;
            color: #666;
        }
    </style>
</head>
<body>

<h1>Library Manager</h1>

<button onclick="window.location.href='index.php'">Back to Home</button>

<h2>Add a New Book</h2>
<form action="createBooks.php" method="POST">
    <input type="text" name="title" placeholder="Title" required>
    <input type="text" name="author" placeholder="Author" required>
    <input type="number" name="year" placeholder="Year" required min="1000" max="2025">
    <button type="submit">Add Book</button>
</form>

<h2>Library Catalog</h2>
<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by title or author">
    <button type="submit">Search</button>
</form>

<?php
if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = "%{$_GET['search']}%";
    $stmt = $conn->prepare("SELECT id, title, author, year FROM books WHERE title LIKE ? OR author LIKE ?");
    $stmt->bind_param("ss", $search, $search);
} else {
    $stmt = $conn->prepare("SELECT id, title, author, year FROM books");
}
$stmt->execute();
$result = $stmt->get_result();

echo "<table>
<tr>
    <th>Title</th>
    <th>Author</th>
    <th>Year</th>
    <th>Actions</th>
</tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = (int)$row['id'];
        echo "<tr data-id='$id'>
            <td class='title'>" . htmlspecialchars($row['title']) . "</td>
            <td class='author'>" . htmlspecialchars($row['author']) . "</td>
            <td class='year'>" . htmlspecialchars($row['year']) . "</td>
            <td>
                <a href='#' onclick='openEditModal($id)'>Edit</a> | 
                <a href='#' onclick='deleteBook(event, $id)'>Delete</a> | 
                <a href='borrowHistory.php?id=$id'>History</a>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No books found</td></tr>";
}

echo "</table>";
?>

<!-- Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeEditModal()">×</span>
        <div id="editContent">Loading...</div>
    </div>
</div>

<script>
function openEditModal(bookId) {
    fetch("editBooks.php?id=" + bookId)
        .then(response => response.text())
        .then(html => {
            document.getElementById("editContent").innerHTML = html;
            document.getElementById("editModal").style.display = "block";

            const form = document.getElementById("editForm");
            if (form) {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);

                    fetch("editBooks.php", {
                        method: "POST",
                        body: formData,
                        headers: { "Accept": "application/json" }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            const row = document.querySelector(`tr[data-id='${data.id}']`);
                            if (row) {
                                row.querySelector(".title").textContent = data.title;
                                row.querySelector(".author").textContent = data.author;
                                row.querySelector(".year").textContent = data.year;
                            }
                            closeEditModal();
                        } else {
                            alert("Failed to update: " + (data.error || "unknown error"));
                        }
                    })
                    .catch(err => alert("Error: " + err));
                });
            }
        });
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

function deleteBook(e, id) {
    e.preventDefault();
    if (!confirm("Are you sure?")) return;

    fetch("deleteBooks.php?id=" + id)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const row = document.querySelector(`tr[data-id='${id}']`);
                if (row) row.remove();
            } else {
                alert("Failed to delete: " + (data.error || "unknown error"));
            }
        })
        .catch(err => alert("Request failed: " + err));
}
</script>

</body>
</html>
