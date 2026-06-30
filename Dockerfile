FROM php:8.2-apache

ARG WWWGROUP=1000
ARG WWWUSER=1000
ARG GIT_USER_EMAIL=jiri.wolf@jw.cz
ARG GIT_USER_NAME=Wolf
ARG NODE_VERSION=20.x

# Base image má www-data:33 – přemapujeme UID/GID na požadované hodnoty
RUN set -eux; \
    groupmod -g ${WWWGROUP} www-data; \
    usermod -u ${WWWUSER} -s /bin/bash -d /home/www-data www-data; \
    mkdir -p /home/www-data && chown ${WWWUSER}:${WWWGROUP} /home/www-data

WORKDIR /var/www/html

# Systémové závislosti a PHP rozšíření
RUN apt-get update && apt-get install -y --no-install-recommends \
    default-mysql-client \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    git \
    wget \
    curl \
    gnupg \
    ca-certificates \
    vim \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install \
        bcmath \
        gd \
        intl \
        opcache \
        pdo \
        pdo_mysql \
        mysqli \
        zip \
        soap \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Apache moduly – mod_php z base image funguje s mpm_prefork
# HTTP/2 vyžaduje php-fpm + mpm_event, ale php:8.2-apache image
# má PHP zkompilované ze zdrojáků (ne Debian balíčky), proto nelze
# nainstalovat php8.2-fpm z Sury repo. Pro dev prostředí stačí
# mpm_prefork + mod_php s podporou rewrite a setenvif.
RUN a2enmod rewrite setenvif

# GitHub CLI
RUN curl -fsSL https://cli.github.com/packages/githubcli-archive-keyring.gpg \
      | dd of=/usr/share/keyrings/githubcli-archive-keyring.gpg \
    && chmod go+r /usr/share/keyrings/githubcli-archive-keyring.gpg \
    && echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/githubcli-archive-keyring.gpg] https://cli.github.com/packages stable main" \
      > /etc/apt/sources.list.d/github-cli.list \
    && apt-get update \
    && apt-get install -y --no-install-recommends gh \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Google Chrome (headless testování + Chrome DevTools MCP)
RUN wget -q -O /tmp/chrome.deb https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb \
    && apt-get update \
    && apt-get install -y /tmp/chrome.deb \
    && rm /tmp/chrome.deb \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION} | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Claude Code (globálně)
RUN npm install -g @anthropic-ai/claude-code || true

# Composer
RUN wget -q https://getcomposer.org/installer -O /tmp/composer-setup.php \
    && php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm /tmp/composer-setup.php

# Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && cat > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini << 'EOF'
[xdebug]
zend_extension=xdebug
xdebug.mode=develop,debug
xdebug.client_host=host.docker.internal
xdebug.client_port=9003
xdebug.start_with_request=yes
xdebug.discover_client_host=0
xdebug.idekey=PHPSTORM
EOF

# Git konfigurace
RUN git config --global user.email "${GIT_USER_EMAIL}" \
    && git config --global user.name "${GIT_USER_NAME}" \
    && git config --global init.defaultBranch master \
    && git config --global --add safe.directory /var/www/html

# Drush symlink
RUN ln -s /var/www/html/vendor/bin/drush /usr/local/bin/drush || true

# Domovský adresář + gh-copilot extension
RUN mkdir -p /home/www-data \
    && chown -R ${WWWUSER}:${WWWGROUP} /home/www-data
RUN su -s /bin/bash www-data -c 'gh extension install github/gh-copilot --yes' || true
RUN chown -R ${WWWUSER}:${WWWGROUP} /home/www-data || true

# /run/php-fpm musí existovat před startem (www-data nemá práva vytvářet složky v /run/)
RUN mkdir -p /run/php-fpm \
    && chown www-data:www-data /run/php-fpm

# Entrypoint skript (spustí Chrome + Apache)
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Ownership workspace
RUN chown -R www-data:www-data /var/www/html

USER www-data

EXPOSE 80 3100

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
