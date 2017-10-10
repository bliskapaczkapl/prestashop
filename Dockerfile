FROM prestashop/prestashop:1.6
MAINTAINER Mateusz Koszutowski <mkoszutowski@divante.pl>

ENV prestashop_path /var/www/html

RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y \
    wget \
    curl \
    git \
    apt-utils \
    sudo \
    mysql-server

# MySQL
RUN service mysql start \
    && mysqladmin -uroot password prestashop \
    && mysql -uroot -pprestashop -e 'CREATE DATABASE IF NOT EXISTS prestashop'

# Copy Prestashop configuration
COPY settings.inc.php ${prestashop_path}/config/settings.inc.php

# Copy latest version of Bliskapaczka module
COPY modules ${prestashop_path}/modules
COPY vendor ${prestashop_path}/vendor

# Change dir permisions
RUN chmod 777 ${prestashop_path}/log
RUN chmod 777 ${prestashop_path}/config/
RUN chmod -R 777 ${prestashop_path}/cache/
RUN chmod -R 777 ${prestashop_path}/img/
RUN chmod -R 777 ${prestashop_path}/mails/
RUN chmod -R 777 ${prestashop_path}/modules/
RUN chmod -R 777 ${prestashop_path}/override/
RUN chmod 777 ${prestashop_path}/themes/default-bootstrap/lang/
RUN chmod 777 ${prestashop_path}/themes/default-bootstrap/pdf/lang/
RUN chmod 777 ${prestashop_path}/themes/default-bootstrap/cache/
RUN chmod -R 777 ${prestashop_path}/translations/
RUN chmod 777 ${prestashop_path}/upload/
RUN chmod 777 ${prestashop_path}/download/

RUN service mysql start \
	&& php install/index_cli.php --domain=localhost:8080 --db_server=localhost --db_name=prestashop --db_user=root --db_password=prestashop \
	&& service mysql stop

RUN rm -rf ${prestashop_path}/install
RUN mv ${prestashop_path}/admin ${prestashop_path}/admin6666ukv7e

COPY run /opt/run

EXPOSE 80

CMD bash /opt/run