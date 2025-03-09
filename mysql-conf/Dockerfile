FROM mysql:5.7

# 호스트의 my.cnf를 이미지 안으로 복사
COPY my.cnf /etc/mysql/my.cnf

# 권한과 소유자 설정 (리눅스 파일시스템 상에서 동작)
RUN chown root:root /etc/mysql/my.cnf && \
    chmod 644 /etc/mysql/my.cnf

RUN yum install -y net-tools
