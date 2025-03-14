<?php
    session_start();
    include 'db.php';
    $nickname = $_SESSION['nickname'];
    if(isset($_SESSION['login_id'])){
        echo 'Welcome to world ' . $nickname;
        echo '<a href="logout.php">로그아웃</a>';
    }
    else {
        echo '<a href="login.php">로그인</a>';
        echo '<a href="register.php">회원가입</a>';
    }
    $totalSql = "SELECT COUNT(*) FROM posts WHERE deleted_at IS NULL";
    $totalStmt = $pdo->query($totalSql);
    $totalPosts = $totalStmt->fetchColumn();

    $sql = 'SELECT p.*, u.nickname
            FROM posts p
            JOIN users u ON p.writer = u.login_id
            WHERE p.deleted_at IS NULL
            ORDER BY p.created_at DESC';
    $stmt = $pdo->query($sql);
?>
<!DOCTYPE html>
<meta charset="UTF-8">
<title>index</title>
<h1>Board</h1>
<a href="write.php">글쓰기</a>

<table border="1" cellspacing="0" cellpadding="8">
    <tr>
        <th>no.</th>
        <th>title</th>
        <th>writer</th>
        <th>date</th>
        <th>views</th>
    </tr>

<?php
    $counter = 1;
    while ($row = $stmt->fetch()){
        $title = htmlspecialchars($row['title']);
        $writer = htmlspecialchars($row['nickname']);
        $created_at = $row['created_at'];
        $views = $row['view_count'];

        // date 처리
        $createdTimestamp = strtotime($row['created_at']);
        $currentTimestamp = time();
        if (date('Y', $createdTimestamp) !== date('Y', $currentTimestamp)) {
            // 연도가 다르면 "년도:월:일"
            $displayDate = date('Y-m-d', $createdTimestamp);
        } elseif (date('Y-m-d', $createdTimestamp) === date('Y-m-d', $currentTimestamp)) {
            // 작성 날짜가 오늘과 같다면 "시:분"
            $displayDate = date('H:i', $createdTimestamp);
        } else {
            // 연도는 같지만 날짜가 다르면 "월:일"
            $displayDate = date('m/d', $createdTimestamp);
        }

        // 게시물 출력
        echo "<tr onclick=\"window.location.href='view.php?id={$row['id']}'\" style='cursor:pointer;'>";
        echo "<td>" . $totalPosts-- . "</td>";
        echo "<td>{$title}</td>";
        echo "<td>{$writer}</td>";
        echo "<td>{$displayDate}</td>";
        echo "<td>{$views}</td>";
        echo "</tr>";
        
        $counter++;
    }
?>
</table>
