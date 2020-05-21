FROM node:8-slim as nodeBuild

WORKDIR /treten

COPY package.json package.json

RUN npm install


FROM php:7.3-fpm

WORKDIR /treten

RUN apt-get update && docker-php-ext-install pdo_mysql

COPY --from=nodeBuild /treten/node_modules /treten/node_modules

COPY . .

RUN apt-get install sqlite3 libsqlite3-dev -y && docker-php-ext-install pdo_sqlite

EXPOSE 9000

COPY docker-entrypoint.sh /usr/local/bin/

ENTRYPOINT [ "sh", "docker-entrypoint.sh" ]

CMD [ "php-fpm" ]
