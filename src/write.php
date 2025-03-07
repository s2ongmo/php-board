<?php
    session_start();
    include 'db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (isset($_POST['title']) && isset($_POST['content'])){
            $title = htmlspecialchars($_POST['title']);
            $content = htmlspecialchars($_POST['content']);
            $nickname = $_SESSION['nickname'];

            // 플레이스홀더를 :writer로 변경
            $sql = 'INSERT INTO posts (writer, title, content) VALUES (:writer, :title, :content)';
            $stmt = $pdo->prepare($sql);

            try {
                $stmt->execute([
                    ':writer' => $nickname,
                    ':title'  => $title,
                    ':content'=> $content
                ]);
                header('Location: index.php');
                exit;
            } catch (PDOException $e) {
                die('write error! ' . $e->getMessage());
            }
        } else {
            header('Location: write.php');
            exit;
        }
    }
?>
<form method="POST" action="">
    <input type="text" name="title" id="title"><br>
    <textarea name="content" id="content"></textarea>
    <input type="submit" value="submit">
</form>
