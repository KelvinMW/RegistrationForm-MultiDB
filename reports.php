<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports Page</title>
</head>
<body>
    <h2>Reports</h2>
    <p>This is the reports page. Content will be added here.</p>
    <nav>
        <ul>
            <li><a href="landing.php">Home</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>
    <a href="logout.php">Logout</a>
</body>
</html>
