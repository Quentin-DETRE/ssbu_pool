FROM mariadb:10.5.8

# Fix permissions error
RUN usermod -u 1000 mysql
RUN mkdir -p /var/run/mysqld
RUN chmod -R 777 /var/lib/mysql
