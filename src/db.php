<?php
// 데이터베이스 연결 정보 설정
$host    = '172.18.0.2'; // docker-db IP
$dbname  = 'board_db';
$user    = 'boarduser';
$pass    = 'boardpass';
$charset = 'utf8mb4';

// data source name == dsn
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // 에러 발생 시 예외를 던짐
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch 결과를 연관 배열로 반환
    PDO::ATTR_EMULATE_PREPARES   => false,                  // 원본 prepared statements 사용
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $pdo->exec("SET time_zone = 'Asia/Seoul'");

} catch (PDOException $e) {
    // 연결 실패 시 에러 메시지 출력
    die("데이터베이스 연결 실패: " . $e->getMessage());
}
?>