-- 데이터베이스 생성 시점에 문자셋 지정 (이미 생성된 경우 생략 가능)
CREATE DATABASE IF NOT EXISTS board_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE board_db;

-- users 테이블: login_id는 고유 식별자로 사용, nickname은 화면에 표시할 이름
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login_id VARCHAR(50) NOT NULL UNIQUE,
    nickname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- posts 테이블: writer 컬럼은 users.login_id를 외래키로 연결
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    writer VARCHAR(50) NOT NULL,  -- 여기에 로그인한 사용자의 login_id를 저장 (외래키)
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL,
    view_count INT DEFAULT 0,
    FOREIGN KEY (writer) REFERENCES users(login_id)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- comments 테이블: comment 작성자의 경우는 users.id(고유 번호)를 참조 (필요에 따라 변경 가능)
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL,
    FOREIGN KEY (post_id) REFERENCES posts(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE files(
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL,
    FOREIGN KEY (post_id) REFERENCES posts(id)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;