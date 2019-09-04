
FROM php:7.3-apache
RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install mysqli


RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-configure zip --with-libzip \
  && docker-php-ext-install zip

RUN apt-get install -y zlib1g-dev libicu-dev g++ \
        && docker-php-ext-configure intl \
        && docker-php-ext-install intl

RUN apt-get update -y && apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
    libfreetype6-dev
RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev 
RUN docker-php-ext-configure gd --with-gd --with-webp-dir --with-jpeg-dir \
    --with-png-dir --with-zlib-dir --with-xpm-dir --with-freetype-dir \
    --enable-gd-native-ttf

RUN docker-php-ext-install gd

RUN apt-get update && \
    apt-get install -y libxml2-dev && \
    docker-php-ext-install soap

RUN apt-get update && \
    apt-get install -y libxml2-dev && \
    docker-php-ext-install opcache


# Git
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

# RUN mkdir /var/www/test

# RUN git -C /var/www/test clone https://github.com/vinaykr4321/customboost-moodletheme.git

# RUN chown -R www-data:www-data /var/www/test

# Copy
RUN mkdir /var/www/quicklxnew
COPY ./quicklxnew /var/www/quicklxnew
RUN chown -R www-data:www-data /var/www/quicklxnew


# VOLUME /var/www/quicklxnew
# # RUN mkdir /var/www/html/test
# # RUN chown -R www-data /var/www/quicklxnew


# RUN chown -R www-data /var/www/quicklxnew 