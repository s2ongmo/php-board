<?php
session_start();
if (!isset($_SESSION['error_message'])) {
    header('Location: index.php');
    exit;
}
$error_message = $_SESSION['error_message'];
unset($_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
</head>
<body>
    <h1>An error occurred</h1>
    <p><?= htmlspecialchars($error_message) ?></p>
    <a href="index.php">Go back</a>
</body>
</html>