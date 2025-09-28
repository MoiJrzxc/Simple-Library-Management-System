<?php
session_start();
include "db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header("Location: index.php");
    exit();
}
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
        <span class="nav-title">iGit Library Manager</span>
    </div>
    <div class="nav-right">
        <a href="#" class="nav-link" onclick="openAddModal()">Add Book</a>
        <a href="borrowHistory.php" class="nav-link">View Borrow/Return History</a>
        <a href="index.php" class="nav-link">Log out</a>
    </div>
</nav>

<div class="container">
    <h2>Library Catalog</h2>
    <form method="GET" action="" class="form-inline flex-space-reverse">
        <div class="search-group">
            <input type="text" name="search" placeholder="Search by title or author">
            <button type="submit" class="history">Search</button>
        </div>
    </form>

    <?php
    if (isset($_GET['search']) && $_GET['search'] !== "") {
        $search = "%{$_GET['search']}%";
        $stmt = $conn->prepare("SELECT id, title, author, year FROM books WHERE deleted=0 AND (title LIKE ? OR author LIKE ?)");
        $stmt->bind_param("ss", $search, $search);
    } else {
        $stmt = $conn->prepare("SELECT id, title, author, year FROM books WHERE deleted=0");
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
                    <a href='#' class='action-link' onclick='openEditModal($id)'>Edit</a> 
                    <a href='#' class='action-link' onclick='deleteBook(event, $id)'>Delete</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No books found</td></tr>";
    }

    echo "</table>";
    ?>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeEditModal()">×</span>
            <div id="editContent">Loading...</div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeAddModal()">×</span>
            <h3>Add a New Book</h3>
            <form id="addForm">
                <label>Title:
                    <input type="text" name="title" required>
                </label>
                <label>Author:
                    <input type="text" name="author" required>
                </label>
                <label>Year:
                    <input type="number" name="year" required min="1000" max="2025">
                </label>
                <button type="submit">Add Book</button>
            </form>
        </div>
    </div>
</div>

<script>
// ---------- EDIT -----------
function openEditModal(bookId) {
    fetch("editBooks.php?id=" + bookId)
        .then(response => response.text())
        .then(html => {
            document.getElementById("editContent").innerHTML = html;
            document.getElementById("editModal").classList.add("show");

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
    document.getElementById("editModal").classList.remove("show");
}

// ---------- ADD ----------
function openAddModal() {
    document.getElementById("addModal").classList.add("show");
}
function closeAddModal() {
    document.getElementById("addModal").classList.remove("show");
}

document.getElementById("addForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch("createBooks.php", {
        method: "POST",
        body: formData
    })
    .then(() => {
        // Refresh the page to show new book (simplest option)
        location.reload();
    })
    .catch(err => alert("Error: " + err));
});

// ---------- DELETE ----------
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
