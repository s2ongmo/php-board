# PHP와 Apache가 포함된 공식 이미지를 사용합니다.
FROM php:7.4-apache

# 패키지 목록 업데이트 후, 필요한 패키지(net-tools, vim)를 설치합니다.
RUN apt-get update && apt-get install -y \
    net-tools \
    vim

# MySQL 연동을 위한 확장 설치 (mysqli, pdo_mysql)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 프로젝트 소스 코드를 컨테이너 내 웹 루트로 복사
COPY src/ /var/www/html/

# Apache 설정을 추가로 수정할 필요가 있으면 설정 파일을 복사할 수 있습니다.
# 예: COPY config/apache.conf /etc/apache2/sites-available/000-default.conf
