<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];

    $stmt = $pdo->prepare("UPDATE users SET active = 1 WHERE username = ?");
    $stmt->execute([$username]);

    $success = "User activated successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Activate User</title>
</head>
<body>
    <h2>Activate User</h2>
    <?php if (isset($success)) { echo "<p style='color:green;'>$success</p>"; } ?>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <button type="submit">Activate</button>
    </form>
</body>
</html>
