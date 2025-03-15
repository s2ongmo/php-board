<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];
        
        // 조회수 증가
        if (!isset($_SESSION['viewed_' . $post_id])){
            $sql = 'UPDATE posts SET view_count = view_count + 1 WHERE id = :post_id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':post_id' => $post_id]);
            $_SESSION['viewed_' . $post_id] = true;
        }

        // p.writer(게시글 작성자, 실제로는 users.login_id 값)와 u.nickname, u.id(사용자 고유번호) 모두 가져옴
        $sql = 'SELECT p.*, u.nickname, u.id as user_id, f.file_path
                FROM posts p
                JOIN users u ON p.writer = u.login_id
                LEFT JOIN files f ON p.id = f.post_id
                WHERE p.id = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $post_id]);
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

    // 내용출력
    echo '<h1>' . htmlspecialchars($post['title']) . '</h1>';
    echo '<p>' . nl2br(htmlspecialchars($post['content'])) . '</p>';
    if ($post['file_path']){
        echo '<img src="' . $post['file_path'] . '" width="400px" alt="첨부파일">'; // 이미지 출력
    }
    echo '<p>작성자: ' . htmlspecialchars($post['nickname']) . '</p>';
    echo '<p>작성일: ' . $displayDate . '</p>';
    echo '<p>조회수: ' . htmlspecialchars($post['view_count']) . '</p>';
    echo '<a href="index.php">목록으로</a>';

    // 글 삭제, 수정 버튼 출력 (작성자와 로그인 아이디가 일치해야 함)
    if (isset($_SESSION['login_id']) && $_SESSION['login_id'] == $post['writer']) {
        echo '<form action="deletePost.php" method="post">';
        echo '<input type="hidden" name="post_id" value="' . htmlspecialchars($post['id']) . '">';
        echo '<button type="submit">글 삭제</button>';
        echo '</form>';
        echo '<form action="edit.php" method="post">';
        echo '<input type="hidden" name="post_id" value="' . htmlspecialchars($post['id']) . '">';
        echo '<input type="hidden" name="login_id" value="' . htmlspecialchars($post['writer']) . '">';
        echo '<button type="submit">글 수정</button>';
        echo '</form>';

    }
}
?>
<!-- 댓글 작성 폼 -->
<form action="comment.php" method="post">
    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
    <textarea name="content" required></textarea>
    <button type="submit">댓글 작성</button>
</form>
<?php
    // 댓글 조회
    $sql = 'SELECT c.*, u.nickname, u.login_id 
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_id = :post_id AND c.deleted_at IS NULL
            ORDER BY c.created_at DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':post_id' => $post['id']]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($comments as $comment) {
        $createdTimestamp = strtotime($comment['created_at']);
        $currentTimestamp = time();
    
        if (date('Y', $createdTimestamp) !== date('Y', $currentTimestamp)) {
            // 연도가 다르면 "년도:월:일"
            $displayDate = date('Y:m:d', $createdTimestamp);
        } elseif (date('Y-m-d', $createdTimestamp) === date('Y-m-d', $currentTimestamp)) {
            // 작성 날짜가 오늘과 같다면 "시:분"
            $displayDate = date('H:i', $createdTimestamp);
        } else {
            // 연도는 같지만 날짜가 다르면 "월:일"
            $displayDate = date('m:d', $createdTimestamp);
        }
        
    
        echo '<p>' . htmlspecialchars($comment['nickname']) . ' (' . $displayDate . ')</p>';
        // 댓글 삭제 
        if (isset($_SESSION['login_id']) && $_SESSION['login_id'] == $comment['login_id']) {
            echo '<form action="deleteComment.php" method="post">';
            echo '<input type="hidden" name="comment_id" value="' . htmlspecialchars($comment['id']) . '">';
            echo '<input type="hidden" name="post_id" value="' . htmlspecialchars($comment['post_id']) . '">';
            echo '<button type="submit">댓글 삭제</button>';
            echo '</form>';
        }
        echo '<p>' . nl2br(htmlspecialchars($comment['content'])) . '</p>';
    }
?>
