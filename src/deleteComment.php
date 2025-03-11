<?php
    session_start();
    include 'db.php';

    if (isset($_POST['comment_id']) && isset($_POST['post_id'])) {
        $comment_id = $_POST['comment_id'];
        $sql = 'UPDATE comments SET deleted_at=NOW() WHERE id = :comment_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':comment_id' => $comment_id]);
    }
    header('Location: view.php?id=' . $_POST['post_id']);
?>