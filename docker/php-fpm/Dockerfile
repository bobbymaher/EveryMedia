FROM everymedia_basephp

COPY php-fpm.conf /etc/php/php-fpm.conf
COPY entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

EXPOSE 9000

WORKDIR "/var/www/everymedia"

ENTRYPOINT ["/usr/local/bin/entrypoint"]
