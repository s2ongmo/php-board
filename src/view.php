<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        // posts의 writer(닉네임)와 users.nickname을 기준으로 JOIN하여
        // 해당 사용자의 고유 id(user_id)를 가져옵니다.
        $sql = 'SELECT p.*, u.id AS user_id 
                FROM posts p 
                JOIN users u ON p.writer = u.nickname 
                WHERE p.id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$post) {
        echo '게시글이 존재하지 않습니다.';
        exit;
    }

    // 날짜 포맷팅: 오늘이면 시:분, 아니면 Y-m-d
    if (date('Y-m-d', strtotime($post['created_at'])) === date('Y-m-d')) {
        $displayDate = date('H:i', strtotime($post['created_at']));
    } else {
        $displayDate = date('Y-m-d', strtotime($post['created_at']));
    }

    echo '<h1>' . htmlspecialchars($post['title']) . '</h1>';
    echo '<p>' . nl2br(htmlspecialchars($post['content'])) . '</p>';
    echo '<p>작성자: ' . htmlspecialchars($post['writer']) . '</p>';
    echo '<p>작성일: ' . $displayDate . '</p>';
    echo '<p>조회수: ' . htmlspecialchars($post['view_count']) . '</p>';

    // 로그인 시 세션에 저장된 user_id와 JOIN으로 가져온 user_id를 비교
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']) {
        echo '<form action="delete.php" method="post">';
        echo '<input type="hidden" name="id" value="' . htmlspecialchars($post['id']) . '">';
        echo '<button type="submit">글 삭제</button>';
        echo '</form>';
    }
}
?>
