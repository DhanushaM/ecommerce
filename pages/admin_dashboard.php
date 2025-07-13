<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}
?>

<h2>Welcome, Admin!</h2>
<a href="adminlogout.php">Logout</a>
