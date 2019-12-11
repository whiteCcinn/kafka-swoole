FROM php:7.1.22

LABEL maintainer="ccinn <471113744@qq.com>" version="1.0"

# --build-arg timezone=Asia/Shanghai
ARG timezone
# default use www-data user
ARG work_user=www-data

ENV TIMEZONE=${timezone:-"Asia/Shanghai"} \
    PHPREDIS_VERSION=4.3.0 \
    SWOOLE_VERSION=4.4.7 \
    SNAPPY_VERSION=0.1.3 \
    COMPOSER_ALLOW_SUPERUSER=1

# Libs -y --no-install-recommends
RUN apt-get update \
    && apt-get install -y \
        curl wget git zip unzip less vim procps lsof tcpdump htop openssl \
        libz-dev \
        libssl-dev \
        libnghttp2-dev \
        libpcre3-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        strace \
# Install PHP extensions
    && docker-php-ext-install \
       bcmath gd pdo_mysql mbstring sockets zip sysvmsg sysvsem sysvshm

# Install composer
Run curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update --clean-backups \
# Install snappy extension
    && wget https://github.com/kjdev/php-ext-snappy/archive/${SNAPPY_VERSION}.zip -O snappy.tar.gz \
    && mkdir -p snappy \
    && unzip snappy.tar.gz -d snappy \
    && rm snappy.tar.gz \
    && ( \
        cd snappy/php-ext-snappy-${SNAPPY_VERSION} \
        && phpize \
        && ./configure \
        && make -j$(nproc) \
        && make install \
    ) \
    && rm -r snappy \
    && docker-php-ext-enable snappy \
# Install redis extension
    && wget http://pecl.php.net/get/redis-${PHPREDIS_VERSION}.tgz -O /tmp/redis.tar.tgz \
    && pecl install /tmp/redis.tar.tgz \
    && rm -rf /tmp/redis.tar.tgz \
    && docker-php-ext-enable redis \
# Install swoole extension
    && wget https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz -O swoole.tar.gz \
    && mkdir -p swoole \
    && tar -xf swoole.tar.gz -C swoole --strip-components=1 \
    && rm swoole.tar.gz \
    && ( \
        cd swoole \
        && phpize \
        && ./configure --enable-mysqlnd --enable-sockets --enable-openssl --enable-http2 \
        && make -j$(nproc) \
        && make install \
    ) \
    && rm -r swoole \
    && docker-php-ext-enable swoole \
# Clear dev deps
    && apt-get clean \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
# Timezone
    && cp /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    && echo "[Date]\ndate.timezone=${TIMEZONE}" > /usr/local/etc/php/conf.d/timezone.ini

# Install composer deps
ADD . /data/www
RUN  cd /data/www \
    && composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/ \
    && composer install \
    && composer install \
    && composer clearcache

WORKDIR /data/www
