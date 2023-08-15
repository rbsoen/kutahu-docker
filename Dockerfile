FROM nouphet/docker-php4

COPY Aplikasi /var/www/html/
COPY php.ini /etc/php.ini
WORKDIR /var/www/html

# perbaiki zona waktu
ENV TZ Asia/Jakarta
RUN echo "$TZ" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata

# semua skrip
RUN chown -R www-data:www-data *
RUN chmod -R 744 *

# upload Direktori Gambar
RUN mkdir Document
RUN chown -R www-data:www-data Document
RUN chmod -R 777 Document

EXPOSE 80

CMD [ "apache2-foreground" ]
