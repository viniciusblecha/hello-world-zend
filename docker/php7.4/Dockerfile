#FROM phpearth/docker-php:7.4-apache
#RUN apt-get update && apt-get install -y \
#        libfreetype6-dev \
#        libjpeg62-turbo-dev \
#        libpng-dev \
#        libxml2-dev \
#    && docker-php-ext-configure gd --with-freetype --with-jpeg \
#    && docker-php-ext-install -j$(nproc) gd \
#    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#
#RUN apt-get install -y locales
#RUN apt-get install zip unzip
#
#RUN sed -i -e 's/# pt_BR.UTF-8 UTF-8/pt_BR.UTF-8 UTF-8/' /etc/locale.gen && \
#    locale-gen
#ENV LANG pt_BR.UTF-8
#ENV LANGUAGE pt_BR:pt
#ENV LC_ALL pt_BR.UTF-8
#
## PHP Extensions
#RUN docker-php-ext-install pdo pdo_mysql \
# && docker-php-ext-install gd \
# && docker-php-ext-install pcntl \
# && docker-php-ext-install calendar \
# && docker-php-ext-configure calendar \
# && docker-php-ext-install soap
#
## Memory Limit
#RUN echo "memory_limit=2048M" >> $PHP_INI_DIR/conf.d/memory-limit.ini
#RUN echo "max_execution_time=900" >> $PHP_INI_DIR/conf.d/memory-limit.ini
#RUN echo "post_max_size=20M" >> $PHP_INI_DIR/conf.d/memory-limit.ini
#RUN echo "upload_max_filesize=20M" >> $PHP_INI_DIR/conf.d/memory-limit.ini
#
#COPY ./* /var/www/html/
##
###CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
##
#EXPOSE 80


FROM php:7.4-apache
LABEL maintainer="dev@chialab.io"

# Download script to install PHP extensions and dependencies
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync

RUN DEBIAN_FRONTEND=noninteractive apt-get update -q \
    && DEBIAN_FRONTEND=noninteractive apt-get install -qq -y \
      curl \
      git \
      zip unzip \
    && install-php-extensions \
      bcmath \
      bz2 \
      calendar \
      exif \
      gd \
      intl \
      ldap \
      memcached \
      mysqli \
      opcache \
      pdo_mysql \
      pdo_pgsql \
      pgsql \
      redis \
      soap \
      xsl \
      zip \
      sockets \
      pdo_sqlsrv \
      sqlsrv \
# already installed:
#      iconv \
#      mbstring \
    && a2enmod rewrite

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && ln -s $(composer config --global home) /root/composer
ENV PATH=$PATH:/root/composer/vendor/bin COMPOSER_ALLOW_SUPERUSER=1

# Install prestissimo (composer plugin). Plugin that downloads packages in parallel to speed up the installation process
# After release of Composer 2.x, remove prestissimo, because parallelism already merged into Composer 2.x branch:
# https://github.com/composer/composer/pull/7904
RUN composer global require hirak/prestissimo
