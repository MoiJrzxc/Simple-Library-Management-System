<?php
// redirect to login page and destroy session
session_start();
session_destroy();
header("Location: index.php");
exit();
?>
