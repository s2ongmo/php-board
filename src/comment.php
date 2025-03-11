<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('ERROR: Invalid request');
}

// 댓글 내용 검증
if (!isset($_POST['content']) || empty(trim($_POST['content']))) {
    die('ERROR: content is required');
}

// post_id 값 검증 (정수형)
if (!isset($_POST['post_id']) || !is_numeric($_POST['post_id'])) {
    die('ERROR: Invalid post ID');
}
$post_id = (int) $_POST['post_id'];

// 로그인 상태 검증 및 세션에서 user_id 가져오기
if (!isset($_SESSION['login_id']) || empty($_SESSION['login_id'])) {
    die('ERROR: Login required');
}
$user_id = $_SESSION['user_id'];

// 댓글 내용 처리
$content = htmlspecialchars(trim($_POST['content']));

$sql = 'INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':post_id' => $post_id, 
    ':user_id' => $user_id, 
    ':content' => $content
]);

header('Location: view.php?id=' . $post_id);
exit;
?>
