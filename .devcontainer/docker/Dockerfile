FROM composer:2 AS composer
FROM wordpress:cli-2.6 AS wp-cli
FROM wordpress:php7.4-fpm

# Install additional packages.
RUN set -ex; \
    apt-get update \
    && EXTRA_PACKAGES=" \
      ca-certificates \
      default-mysql-client \
      git \
      jq \
      less \
      libicu-dev \
      libzip-dev \
      ssh \
      subversion \
      unzip \
      zip \
    " \
    && apt-get install -y $EXTRA_PACKAGES --no-install-recommends \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/cache/*

# Install additional PHP extensions
ENV PHP_REDIS_VERSION 5.3.7
ENV PHP_XDEBUG_VERSION 3.1.0
ENV PHP_ZIP_VERSION 1.21.1

RUN set -ex; \
    pecl install "redis-${PHP_REDIS_VERSION}"; \
    pecl install "xdebug-${PHP_XDEBUG_VERSION}"; \
    pecl install "zip-${PHP_ZIP_VERSION}"; \
    docker-php-ext-enable redis xdebug zip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && rm -rf /var/lib/apt/lists/*

# Install wp-cli
COPY --from=wp-cli /usr/local/bin/wp /usr/local/bin/
RUN set -ex; \
    chmod +x /usr/local/bin/wp \
    && wp cli info

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer /usr/bin/composer /usr/local/bin/
COPY --from=composer /tmp/keys.* /tmp/
RUN set -ex; \
    chmod +x /usr/local/bin/composer \
    && COMPOSER_HOME=$(composer global config home) \
    && mv /tmp/keys* $COMPOSER_HOME \
    && composer --ansi --version --no-interaction \
    && composer diagnose
