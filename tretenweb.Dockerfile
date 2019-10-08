FROM nginx

COPY ./nginx.conf ./etc/nginx/conf.d/default.conf

WORKDIR treten

COPY  ./public public
