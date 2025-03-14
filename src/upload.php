<?php
    session_start();
    include 'db.php';

    //파일 정보 저장
    $file = $_FILES['file'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];
    // 파일 이름에서 확장자 분리
    $file_ext = explode('.', $file_name); 
    // 확장자를 소문자로 변환
    $file_ext = strtolower(end($file_ext));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($file_ext, $allowed)){
        if ($file_error === 0){
            // 파일 크기 확인 (2MB 이하)
            if ($file_size <= 2097152){
                $file_name_new = uniqid('', true) . '.' . $file_ext;
                $file_path = 'uploads/' . $file_name_new;
                if (move_uploaded_file($file_tmp, $file_path)){
                    $sql = 'INSERT INTO files (file_name, file_destination) VALUES (:file_name, :file_destination)';
                    $stmt = $pdo->prepare($sql);
                    try {
                        $stmt->execute([
                            ':file_name' => $file_name,
                            ':file_path' => $file_path
                        ]);
                    }
                    catch (PDOException $e) {
                        $_SESSION['error_message'] = 'Upload error: ' . $e->getMessage();
                        header('Location: error.php');
                        exit;
                    }
                } else {
                    $_SESSION['error_message'] = 'File move error!';
                    header('Location: error.php');
                    exit;
                }
            } else {
                $_SESSION['error_message'] = 'File size error! (must be 2MB or less)';
                header('Location: error.php');
                exit;
            }
        } else {
            $_SESSION['error_message'] = 'File error!';
            header('Location: error.php');
            exit;
        }
    } else {
        $_SESSION['error_message'] = 'Invalid file type!';
        header('Location: error.php');
        exit;
    }
    
    // 업로드 완료 후 리디렉션
    header('Location: index.php');
    exit;
?>