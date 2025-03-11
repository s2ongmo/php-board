<?php
    ob_start();
    session_start();
    include 'db.php';
    
    $writer = $_POST['writer'];
    var_dump($writer);
    if ($_SESSION['login_id'] !== $writer){
        die('Not authorized');
        echo '<a href="index.php">돌아가기</a>';
    }
    $title = $_POST['title'];
    $content = $_POST['content'];
    $post_id = $_POST['post_id'];
    $sql = 'UPDATE posts SET title = :title, content = :content WHERE id = :post_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':post_id' => $post_id
    ]);
    header('Location: view.php?id=' . $post_id);
    exit;
    ob_end_flush();
?>