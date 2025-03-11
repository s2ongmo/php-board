<?php
    session_start();
    include 'db.php';
    $sql = 'UPDATE posts SET deleted_at = NOW() WHERE id = :post_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':post_id' => $_POST['post_id']]);
    header('Location: index.php');
    exit;
?>
