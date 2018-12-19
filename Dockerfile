FROM ubuntu:16.04

ENV http_proxy http://10.64.22.254:800
ENV https_proxy http://10.64.22.254:800
ENV no_proxy 127.0.0.1,10.0.0.0/24,.objectway.it,.owgroup.it,.objectway.com

RUN groupadd -r usvn && useradd --no-log-init -r -g usvn usvn && \
    apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
        apache2 php libapache2-mod-php mysql-server php-xml php-mysql subversion libapache2-svn zend-framework
ADD src /var/sites/usvn/
COPY usvn.conf /etc/apache2/sites-available/usvn.conf
RUN chown -R usvn:www-data /var/sites/usvn && \
    a2enmod rewrite && \
    a2ensite usvn

EXPOSE 80
CMD apachectl -D FOREGROUND

