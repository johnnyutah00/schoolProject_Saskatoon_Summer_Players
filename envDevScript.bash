#!/bin/bash
#
# Run server in dev environment

echo "APP_ENV=dev
APP_SECRET=090892cefdc0b8c82489231ce79fc975
MAILER_URL=gmail://Saskatoonsummerplayers@gmail.com:SSPwebsite2019@localhost" > .env
/d/PHP/v7.2/php bin/console server:run