FROM node:8-slim as nodeBuild

WORKDIR /treten

COPY ./package.json /treten/package.json

RUN npm install



FROM composer as composerBuild

WORKDIR /treten

COPY database database

COPY tests tests

COPY ./composer.json /treten/composer.json

RUN composer install --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts



FROM php:7.3-fpm

WORKDIR /treten

RUN apt-get update && docker-php-ext-install pdo_mysql

COPY --from=composerBuild /treten/vendor /treten/vendor

COPY --from=nodeBuild /treten/node_modules /treten/node_modules

COPY . .

EXPOSE 9000

COPY ./docker-entrypoint.sh /usr/local/bin/

ENTRYPOINT [ "sh", "docker-entrypoint.sh" ]

CMD [ "php-fpm" ]
