# FROM node:8-slim as nodeBuild

# WORKDIR /treten

# COPY ./package.json /treten/package.json

# RUN npm install



# FROM composer as composerBuild

# WORKDIR /treten

# COPY database database

# COPY tests tests

# COPY ./composer.json /treten/composer.json

# RUN composer install --ignore-platform-reqs \
#     --no-interaction \
#     --no-plugins \
#     --no-scripts



FROM php:7.3-fpm

WORKDIR /treten

RUN apt-get update && \ 
    apt-get install -y libzip-dev zip git nodejs && \
    docker-php-ext-configure zip --with-libzip && \
    docker-php-ext-install pdo pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./composer.json .

COPY ./database/ ./database/

COPY ./tests/ ./tests/

RUN composer global require hirak/prestissimo

RUN composer install --ignore-platform-reqs --no-scripts --no-interaction

RUN curl -sL https://deb.nodesource.com/setup_11.x | bash -

RUN apt-get update && apt-get install -y nodejs

COPY ./package.json .

RUN npm install

# COPY --from=composerBuild /treten/vendor /treten/vendor

# COPY --from=nodeBuild /treten/node_modules /treten/node_modules

COPY . .

EXPOSE 9000

COPY ./docker-entrypoint.sh /usr/local/bin/

ENTRYPOINT [ "sh", "docker-entrypoint.sh" ]

# CMD [ "php-fpm" ]
