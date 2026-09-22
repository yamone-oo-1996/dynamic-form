ARG BASE_IMAGE=devdocker-repo.frontiir.net/frontiir/php8.4:latest

FROM ${BASE_IMAGE} AS build

ARG SSH_PRIVATE_KEY

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apk add --no-cache openssh

RUN mkdir ~/.ssh/ && \
    echo "$SSH_PRIVATE_KEY" > ~/.ssh/id_rsa && \
    chmod 600 ~/.ssh/id_rsa && \
    ssh-keyscan git.frontiir.net >> ~/.ssh/known_hosts

WORKDIR /app

COPY . .

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-dev \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

RUN composer dump-autoload \
    --optimize

RUN rm -rf ~/.ssh

FROM ${BASE_IMAGE} AS web

WORKDIR /var/www

COPY --from=build /app/vendor /var/www/vendor
COPY --from=build /app/composer.json ./composer.json

COPY . .

ENTRYPOINT [ "php", "/var/www/artisan", "serve" , "--host=0.0.0.0", "--port=80"]
