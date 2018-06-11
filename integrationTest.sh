#!/bin/bash

DOCKER="./docker/"
CONFIG_ENV="./src/config/config.env"

cp -rfp $CONFIG_ENV.example $CONFIG_ENV
cd $DOCKER

docker-compose build
docker-compose up -d

docker exec -it app_searchmetrics sh -c "cd /var/www/ && vendor/phpunit/phpunit/phpunit tests/" -d

curl http://127.0.0.1:8081/?url=https://google.de:80/hh

