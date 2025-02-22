# PHP 게시판 프로젝트 :computer:

## 프로젝트 개요 :page_facing_up:
이 프로젝트는 **웹해킹 필수 교양**의 일환으로, 게시판을 직접 제작하여 **공격 벡터**와 기본 웹 애플리케이션 구조를 이해하는 데 목적이 있습니다.  
기본 환경은 **Docker**를 활용하여 **PHP, Apache, MySQL**의 조합으로 구성되어 있습니다.

## 목표 및 일정 :dart:
- **작업 목표:**  
  게시판 제작을 통해 보안 취약점과 공격 벡터를 학습하고, 웹 애플리케이션의 기본 아키텍처를 이해합니다.
- **작업 기간:**  
  **2025.02.23 - 2025.03.23**

## 환경 :gear:
- **Docker:** v27.3.1 
- **PHP:** 7.4 
- **Apache:** v2.4  
- **MySQL:** 5.7 
## 설치 및 실행 방법 :rocket:

### 1. 클론 및 저장소 설정 :package:
```bash
git clone https://github.com/s2ongmo/php-board.git
cd php-board
```

### 2. Docker 컨테이너 실행 :whale:
프로젝트 루트에 있는 `docker-compose.yml` 파일을 이용해 컨테이너를 빌드하고 실행합니다.
```bash
docker-compose up --build
```
실행 후, 웹 브라우저에서 [http://localhost:8080](http://localhost:8080)으로 접속하여 게시판 페이지를 확인하세요.

### 3. 데이터베이스 마이그레이션 :hammer_and_wrench:
데이터베이스 스키마 변경이 필요할 경우, `migrations/` 폴더 내에 SQL 스크립트를 작성합니다.  
작성된 스크립트는 컨테이너 내에서 직접 실행하거나, Flyway, Liquibase 등의 도구를 사용하여 반영할 수 있습니다.

## 업데이트 내역 :sparkles:
업데이트 내용은 Git 커밋 내역을 통해 확인 가능합니다.

## 기여 방법 :handshake:
- **Issue 등록:** 버그, 에러, 개선 사항 등은 GitHub Issue에 등록해 주세요.
- **Pull Request:** 코드 수정이나 기능 추가를 원하시면 Fork 후 PR을 보내주시기 바랍니다.

## 문의 및 이슈 :email:
문의 사항이나 이슈, 에러는 아래 이메일로 연락해 주세요.
- **이메일:** leaveleave01@gmail.com

## 라이선스 :balance_scale:
이 프로젝트는 [MIT 라이선스](LICENSE)를 따릅니다.
