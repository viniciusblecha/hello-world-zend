FROM phpmyadmin/phpmyadmin:latest

# Memory Limit
RUN echo "post_max_size=200M" >> $PHP_INI_DIR/conf.d/memory-limit.ini
RUN echo "upload_max_filesize=200M" >> $PHP_INI_DIR/conf.d/memory-limit.ini