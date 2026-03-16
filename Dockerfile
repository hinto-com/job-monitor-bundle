FROM php:8.3-cli-alpine

WORKDIR /srv/app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache git

RUN mkdir -p /srv/app
RUN git config --global --add safe.directory /srv/app
