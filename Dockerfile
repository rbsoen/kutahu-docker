FROM nouphet/docker-php4

COPY aplikasi/LCMS /var/www/html/
COPY php.ini /etc/php.ini
WORKDIR /var/www/html

# perbaiki zona waktu
ENV TZ Asia/Jakarta
RUN echo "$TZ" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

# untuk sementara waktu berilah semua permission
RUN chmod -R 777 *

EXPOSE 80

CMD [ "apache2-foreground" ]
