<?php
// Search Books Feature

// Database connection
$servername = "db";
$username = "root";
$password = "rootpassword";
$dbname = "testdb";
$conn = new mysqli(hostname: $servername, username: $username, password: $password, database: $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle search form submission
$search = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $search = trim($_POST["search"]);
  $sql = "SELECT title, author, year FROM books WHERE title LIKE ? OR author LIKE ?";
  $stmt = $conn->prepare($sql);
  $likeSearch = "%" . $search . "%";
  $stmt->bind_param("ss", $likeSearch, $likeSearch);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  $result = false;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Books</title>
</head>
<body>
  <h1>Search Books</h1>
  <form method="post" action="searchBooks.php">
    <input type="text" name="search" placeholder="Enter title or author" value="<?php echo htmlspecialchars($search); ?>" required>
    <button type="submit">Search</button>
  </form>

  <?php
  if ($result !== false) {
    if ($result->num_rows > 0) {
      echo "<table border='1'><tr><th>Title</th><th>Author</th><th>Year</th></tr>";
      while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row["title"]) . "</td><td>" . htmlspecialchars($row["author"]) . "</td><td>" . htmlspecialchars($row["year"]) . "</td></tr>";
      }
      echo "</table>";
    } else {
      echo "No books found.";
    }
  }
  ?>

<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>