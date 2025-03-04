# PHP와 Apache가 포함된 공식 이미지를 사용
FROM php:7.4-apache

# utf8mb4 인코딩을 사용하기 위한 MySQL 설정 파일 복사
FROM mysql:5.7
# COPY mysql-conf/my.cnf /etc/mysql/conf.d/utf8mb4.cnf
COPY --chmod=644 mysql-conf/my.cnf /etc/mysql/conf.d/utf8mb4.cnf

# 파일 권한을 644로 설정 (rw-r--r--)
# RUN chmod 644 /etc/mysql/conf.d/utf8mb4.cnf

# MySQL 관련 저장소 파일 제거 (mysql-tools, mysql-shell 등)
RUN rm -f /etc/yum.repos.d/mysql*.repo && yum clean all

# yum 업데이트 및 필요한 패키지 설치
RUN yum update -y && yum install -y --nogpgcheck net-tools vim systemd

# 프로젝트 소스 코드를 컨테이너 내 웹 루트로 복사
COPY src/ /var/www/html/

# Apache 설정을 추가로 수정할 필요가 있으면 설정 파일을 복사할 수 있음음
# 예: COPY config/apache.conf /etc/apache2/sites-available/000-default.conf
