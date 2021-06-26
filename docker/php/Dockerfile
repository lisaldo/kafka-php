FROM phpswoole/swoole:4.6.7-php7.4-dev

RUN apt-get update \
    && apt-get install -y librdkafka-dev \
    && pecl install rdkafka \
    && echo 'extension=rdkafka' > /usr/local/etc/php/conf.d/docker-php-ext-rdkafka.ini