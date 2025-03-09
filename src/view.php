<?php
    session_start();
    include 'db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'GET'){
        # 삭제된 게시물 id 검증 필요
        if (isset($_GET['id'])){
            $id = $_GET['id'];
            $sql = 'SELECT * FROM posts WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        echo '<h1>'. $post['title'] .'</h1>';
        echo '<p>'. $post['content'] .'</p>';
        echo '<p>'. $post['writer'] .'</p>';
        echo '<p>'. $_SESSION['displayDate'] .'</p>';
    }
?>