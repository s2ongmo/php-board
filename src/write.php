<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (trim($_POST['title']) !== '' && trim($_POST['content']) !== ''){
        $title = htmlspecialchars($_POST['title']);
        $content = htmlspecialchars($_POST['content']);
        $writer = $_SESSION['login_id'];

        // 게시글 저장
        $sql = 'INSERT INTO posts (writer, title, content) VALUES (:writer, :title, :content)';
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                ':writer' => $writer,
                ':title'  => $title,
                ':content'=> $content
            ]);
            // 게시글이 성공적으로 저장된 후, 새로 생성된 post_id를 가져옴
            $post_id = $pdo->lastInsertId();
        } catch (PDOException $e) {
            die('게시글 저장 에러: ' . $e->getMessage());
        }
    } else {
        header('Location: write.php');
        exit;
    }

    // 파일이 업로드 되었는지 확인
    if (isset($_FILES['file']) && $_FILES['file']['error'] !== 4) { // error 4: 파일이 업로드되지 않음
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];

        // 파일 이름에서 확장자 분리
        $file_ext = explode('.', $file_name); 
        $file_ext = strtolower(end($file_ext));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed)){
            if ($file_error === 0){
                // 파일 크기 확인 (10MiB 이하)
                if ($file_size <= (1024*1024)*10){
                    $file_name_new = uniqid('', true) . '.' . $file_ext;
                    $file_path = 'uploads/' . $file_name_new;
                    if (move_uploaded_file($file_tmp, $file_path)){
                        // 파일 정보 저장 (post_id, file_name, file_path)
                        $sql = 'INSERT INTO files (post_id, file_name, file_path) VALUES (:post_id, :file_name, :file_path)';
                        $stmt = $pdo->prepare($sql);
                        try {
                            $stmt->execute([
                                ':post_id' => $post_id,
                                ':file_name' => $file_name,
                                ':file_path' => $file_path
                            ]);
                        } catch (PDOException $e) {
                            $_SESSION['error_message'] = 'file upload error: ' . $e->getMessage();
                            header('Location: error.php');
                            exit;
                        }
                    } else {
                        $_SESSION['error_message'] = 'file move error!';
                        header('Location: error.php');
                        exit;
                    }
                } else {
                    $_SESSION['error_message'] = 'file size error!';
                    header('Location: error.php');
                    exit;
                }
            } else {
                $_SESSION['error_message'] = 'file upload error!';
                header('Location: error.php');
                exit;
            }
        } else {
            $_SESSION['error_message'] = 'Unsupported file type!';
            header('Location: error.php');
            exit;
        }
    }

    // 모든 작업이 끝난 후 리디렉션
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Write</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>글 작성</h1>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="text" name="title" id="title" placeholder="제목"><br>
            <textarea name="content" id="content" placeholder="내용"></textarea><br>
            <input type="file" name="file" id="file"><br>
            <input type="submit" value="작성">
        </form>
        <a href="index.php">돌아가기</a>
    </div>
</body>
</html>
