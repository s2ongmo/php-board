<?php
    session_start();
    include 'db.php';
    $sql = 'UPDATE posts SET deleted_at = NOW() WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $_POST['id']]);
    header('Location: index.php');
    exit;
?>
