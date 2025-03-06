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
    
    $sql = 'SELECT posts.*, users.nickname
            FROM posts 
            JOIN users ON posts.writer = users.id
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
            <th>제목</th>
            <th>작성자</th>
            <th>날짜</th>
        </tr>
    
<?php
    // $stmt == iterator 
    while ($row = $stmt->fetch()){
        $title = htmlspecialchars($row['title']);
        $writer = htmlspecialchars($row['writer']);
        $created_at = $row['created_at'];
        
        if (date('Y-m-d', strtotime($created_at)) === date('Y-m-d')) {
            $displayDate = date('H:i:s', strtotime($created_at));
        } else {
            $displayDate = date('Y-m-d', strtotime($created_at));
        }
        
        echo "<tr>";
        echo "<td>{$title}</td>";
        echo "<td>{$nickname}</td>";
        echo "<td>{$displayDate}</td>";
        echo "</tr>";
    }
?>
</table>
    
