FROM       fedora:20
MAINTAINER François Kooman <fkooman@tuxed.net>

# Add OAuth repo to /etc/yum.repos.d
ADD ./php-oauth-as.repo /etc/yum.repos.d/php-oauth-as.repo

# Perform updates
RUN yum -y update; yum clean all

# Install dependencies
RUN yum install -y php-oauth-as php-simple-auth; yum clean all

# Install SSL module
RUN yum install -y mod_ssl; yum clean all

# Allow connections from everywhere
RUN sed -i 's/Require local/Require all granted/' /etc/httpd/conf.d/php-oauth-as.conf
RUN sed -i 's/Require local/Require all granted/' /etc/httpd/conf.d/php-simple-auth.conf

USER apache

# Init Database
RUN php /usr/share/php-oauth-as/bin/initDatabase.php

# Register Clients
ADD ./registration.json /tmp/registration.json
RUN php /usr/share/php-oauth-as/bin/registerClients.php /tmp/registration.json

user root

# Expose port 443 and set httpd as our entrypoint
EXPOSE 443
ENTRYPOINT ["/usr/sbin/httpd"]
CMD ["-D", "FOREGROUND"]
