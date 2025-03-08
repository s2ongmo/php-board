<?php
    ob_start();
    session_start();
    include 'db.php';

    echo '<h1>register</h1>';

    // POST 방식으로 전달된 필수 필드가 모두 존재하고, 비어있지 않은지 검사
    if (
        isset($_POST['login_id'], $_POST['nickname'], $_POST['email'], $_POST['password']) &&
        !empty(trim($_POST['login_id'])) &&
        !empty(trim($_POST['nickname'])) &&
        !empty(trim($_POST['email'])) &&
        !empty(trim($_POST['password']))
    ) {
        $login_id = trim(mb_convert_encoding($_POST['login_id'], 'UTF-8', 'auto'));
        $nickname = trim(mb_convert_encoding($_POST['nickname'], 'UTF-8', 'auto'));
        $email    = trim(mb_convert_encoding($_POST['email'], 'UTF-8', 'auto'));
        $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
        
        
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE login_id = :login_id");
        $checkStmt->execute([':login_id' => $login_id]);
        if($checkStmt->fetchColumn() > 0){
            die("Already exist ID");
        }

        $sql = 'INSERT INTO users (login_id, nickname, email, password) VALUES (:login_id, :nickname, :email, :password)';
        $stmt = $pdo->prepare($sql);

        try{
            $stmt->execute([
                ':login_id' => $login_id,
                ':nickname' => $nickname,
                ':email'    => $email,
                ':password' => $password
            ]);
            header('Location: login.php');
            exit;
        } catch (PDOException $e){
            die("회원가입에 실패했습니다: " . $e->getMessage());
        }
    } else {
        // 값이 없거나 일부가 비어있을 경우, 입력 폼을 출력
        ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <title>register</title>
        <form method="POST" action="">
            <label for="login_id">ID:</label>
            <input type="text" id="login_id" name="login_id" pattern="[a-zA-Z0-9]+" required>
            <br><br>
            <label for="nickname">Name:</label>
            <input type="text" id="nickname" name="nickname" pattern="[a-zA-Z0-9가-힣!@#_\-]+" required>
            <br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="password">PW:</label>
            <input type="password" id="password" name="password" required><br><br>
            
            <input type="submit" value="회원가입">
            <input type="button" onclick="location.href='login.php';" value="로그인">
        </form>
        <?php
    }
    ob_end_flush();
?>
