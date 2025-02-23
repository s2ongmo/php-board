<?php
    session_start();
    include 'db.php';    
    $username = $_SESSION['username'];
    if(isset($_SESSION['login_id'])){
        echo $username . '님 환영합니다';
    }
    else {
        echo '<a href="login.php">로그인</a>';
    }
    
    $sql = 'SELECT posts.*, users.nickname
            FROM posts 
            JOIN users ON posts.writer = users.id
            WHERE posts.deleted_at IS NULL 
            ORDER BY posts.created_at DESC';
    $stmt = $pdo->query($sql);

?>
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
    
