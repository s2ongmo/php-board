<?php
    session_start();
    include 'db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        if ($_POST['title'] !== '' && $_POST['content'] !== ''){
            $title = htmlspecialchars($_POST['title']);
            $content = htmlspecialchars($_POST['content']);
            $writer = $_SESSION['login_id'];

            // 플레이스홀더를 :writer로 변경
            $sql = 'INSERT INTO posts (writer, title, content) VALUES (:writer, :title, :content)';
            $stmt = $pdo->prepare($sql);

            try {
                $stmt->execute([
                    ':writer' => $writer,
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
