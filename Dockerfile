FROM docker.io/centos/httpd
MAINTAINER Joe Sørensen <joe.sorensen@flug.dk>

RUN yum -y install git
RUN git clone https://github.com/JoeX2/flug.dk.git /var/www/html

