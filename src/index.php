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

    $sql = 'SELECT posts.*
            FROM posts 
            WHERE posts.deleted_at IS NULL 
            ORDER BY posts.created_at DESC';
    
    $stmt = $pdo->query($sql);
    
    
?>
    <!DOCTYPE html>
    <meta charset="UTF-8">
    <title>index</title>
    <h1>Board</h1>
    <a href="write.php">글쓰기</a>
    
    <table border="1" cellspacing="0" cellpading="8">
        <tr>
            <th>no.</th>
            <th>title</th>
            <th>writer</th>
            <th>date</th>
            <th>views</th>
        </tr>
    
<?php
    $counter = 1;
    // $stmt == iterator
    while ($row = $stmt->fetch()){
        
        $title = htmlspecialchars($row['title']);
        $writer = htmlspecialchars($row['writer']);
        $created_at = $row['created_at'];
        $views = $row['view_count'];
        if (date('Y-m-d', strtotime($created_at)) === date('Y-m-d')) {
            $displayDate = date('H:i', strtotime($created_at));
        } else {
            $displayDate = date('Y-m-d', strtotime($created_at));
        }

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
    
