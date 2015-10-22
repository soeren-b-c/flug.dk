FROM docker.io/centos/httpd
MAINTAINER Joe Sørensen <joe.sorensen@flug.dk>

RUN yum -y install git
RUN yum -y install php
RUN git clone https://github.com/JoeX2/flug.dk.git /var/www/html

ADD httpd/conf.d/flug.dk.conf /etc/httpd/conf.d/

