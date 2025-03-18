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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- 게시글 영역 -->
        <article class="post">
            <header class="post-header">
                <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                <div class="post-meta">
                    <span class="meta-item">작성자: <?php echo htmlspecialchars($post['nickname']); ?></span>
                    <span class="meta-item">작성일: <?php echo $displayDate; ?></span>
                    <span class="meta-item">조회수: <?php echo htmlspecialchars($post['view_count']); ?></span>
                </div>
            </header>
            <div class="post-content">
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                <?php if ($post['file_path']): ?>
                    <div class="post-image">
                        <img src="<?php echo $post['file_path']; ?>" alt="첨부파일">
                    </div>
                <?php endif; ?>
            </div>
            <?php if (isset($_SESSION['login_id']) && $_SESSION['login_id'] == $post['writer']): ?>
                <div class="button-group">
                    <form action="deletePost.php" method="post" class="btn-form">
                        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['id']); ?>">
                        <button type="submit" class="btn btn-delete">글 삭제</button>
                    </form>
                    <form action="edit.php" method="post" class="btn-form">
                        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['id']); ?>">
                        <input type="hidden" name="login_id" value="<?php echo htmlspecialchars($post['writer']); ?>">
                        <button type="submit" class="btn btn-edit">글 수정</button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="back-link">
                <a href="index.php" class="btn btn-back">목록으로</a>
            </div>
        </article>

        <!-- 댓글 영역 -->
        <section class="comments-section">
            <h2 class="comments-title">댓글</h2>
            <form action="comment.php" method="post" class="comment-form">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <textarea name="content" placeholder="댓글을 입력하세요." required></textarea>
                <button type="submit" class="btn btn-comment">댓글 작성</button>
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
                    $displayDate = date('Y:m:d', $createdTimestamp);
                } elseif (date('Y-m-d', $createdTimestamp) === date('Y-m-d', $currentTimestamp)) {
                    $displayDate = date('H:i', $createdTimestamp);
                } else {
                    $displayDate = date('m:d', $createdTimestamp);
                }
            ?>
                <div class="comment">
                    <div class="comment-header">
                        <span class="comment-author"><?php echo htmlspecialchars($comment['nickname']); ?></span>
                        <span class="comment-date"><?php echo $displayDate; ?></span>
                        <?php if (isset($_SESSION['login_id']) && $_SESSION['login_id'] == $comment['login_id']): ?>
                            <form action="deleteComment.php" method="post" class="comment-delete-form">
                                <input type="hidden" name="comment_id" value="<?php echo htmlspecialchars($comment['id']); ?>">
                                <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($comment['post_id']); ?>">
                                <button type="submit" class="btn btn-delete-comment">삭제</button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="comment-body">
                        <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                    </div>
                </div>
            <?php
            }
            ?>
        </section>
    </div>
</body>
</html>
