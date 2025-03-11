<?php
session_start();
include 'db.php';

$errorMessage = '';

// POST 요청인 경우만 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_id = htmlspecialchars(trim($_POST['login_id']));
    $password = htmlspecialchars(trim($_POST['password']));
    
    // 필수 항목 누락 시 에러 메시지 설정
    if (empty($login_id) || empty($password)) {
        $errorMessage = '<p style="color:red;">login error!</p>';
    } else {
        $sql = 'SELECT * FROM users WHERE login_id = :login_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':login_id' => $login_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // 사용자 정보가 존재하고, 비밀번호 검증 성공 시
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login_id'] = $login_id;
            $_SESSION['nickname'] = htmlspecialchars(trim($user['nickname']));
            header('Location: index.php');
            exit;
        } else {
            $errorMessage = '<p style="color:red;">login error!</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>login</title>
</head>
<body>
    <h1>로그인</h1>
    <!-- 에러 메시지 출력 -->
    <?php
    if (!empty($errorMessage)) {
        echo $errorMessage;
    }
    ?>
    <!-- 로그인 폼 -->
    <form method="POST" action="">
        <label for="login_id">ID:</label>
        <input type="text" id="login_id" name="login_id" required><br><br>
        
        <label for="password">PW:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="button" onclick="location.href='register.php';" value="회원가입">
        <input type="submit" value="로그인">
    </form>
</body>
</html>
