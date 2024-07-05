<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Landing Page</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <p>You have successfully logged in.</p>
    <a href="logout.php">Logout</a>
</body>
</html>
