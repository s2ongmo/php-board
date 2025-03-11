<?php
    session_start();
    include 'db.php';

    if (!isset($_SESSION['login_id']) || $_SESSION['login_id'] !== $_POST['login_id']){
        die('Not authorized');
        echo '<a href="index.php">돌아가기</a>';
        exit;
    }
    $post_id = $_POST['post_id'];
    $sql = 'SELECT * FROM posts WHERE id = :post_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':post_id' => $post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    $post_id = $post['id'];
    $title = $post['title'];
    $content = $post['content'];
    $writer = $post['writer'];
    
?>
<form method="POST" action="update.php">
    <input type="hidden" name="post_id" value="<?= $post_id ?>">
    <input type="hidden" name="writer" value="<?= $writer ?>">
    <textarea name="title" id="title"><?= $title ?></textarea><br>
    <textarea name="content" id="content"><?= $content ?></textarea>
    <input type="submit" value="submit">
</form>
<a href="index.php">돌아가기</a>