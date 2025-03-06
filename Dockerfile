FROM php:7.4-apache

# 프로젝트 소스 코드를 컨테이너 내 웹 루트로 복사
COPY src/ /var/www/html/

# 필요한 패키지 설치 (Debian 기반)
RUN apt-get update && apt-get install -y net-tools vim

# PDO MySQL 확장 설치
RUN docker-php-ext-install pdo_mysql
