<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        
        // 조회수 증가 처리
        if (!isset($_SESSION['viewed_' . $id])){
            $sql = 'UPDATE posts SET view_count = view_count + 1 WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $_SESSION['viewed_' . $id] = true;
        }

        $sql = 'SELECT p.*, u.nickname, u.id AS user_id
                FROM posts p
                JOIN users u ON p.writer = u.login_id
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
    echo '<a href="index.php">목록으로</a>';

    // 글 삭제 버튼 출력
    if (isset($_SESSION['login_id']) && $_SESSION['login_id'] == $post['writer']) {
        echo '<form action="deletePost.php" method="post">';
        echo '<input type="hidden" name="id" value="' . htmlspecialchars($post['id']) . '">';
        echo '<button type="submit">글 삭제</button>';
        echo '</form>';
    }
}
?>
<form action="comment.php" method="post">
    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
    <input type="hidden" name="user_id" value="<?php echo $post['user_id']; ?>">
    <textarea name="content" required></textarea>
    <button type="submit">댓글 작성</button>
</form>
<?php
    $sql = 'SELECT c.*, u.nickname
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_id = :post_id
            ORDER BY c.created_at DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':post_id' => $post['id']]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($comments as $comment) {
        $createdTimestamp = strtotime($comment['created_at']);
        $currentTimestamp = time();
    
        // 연도가 다르면 Y:m:d 형식으로 표시
        if (date('Y', $createdTimestamp) !== date('Y', $currentTimestamp)) {
            $displayDate = date('Y:m:d', $createdTimestamp);
        } 
        // 연도가 같고, 24시간이 안 지나면 시간만 표시
        elseif (($currentTimestamp - $createdTimestamp) < 86400) {
            $displayDate = date('H:i', $createdTimestamp);
        } 
        // 24시간 이상 경과한 경우, m:d 형식으로 표시
        else {
            $displayDate = date('m:d', $createdTimestamp);
        }
    
        echo '<p>' . htmlspecialchars($comment['nickname']) . ' (' . $displayDate . ')</p>';
        echo '<p>' . nl2br(htmlspecialchars($comment['content'])) . '</p>';
    }
    
?>
