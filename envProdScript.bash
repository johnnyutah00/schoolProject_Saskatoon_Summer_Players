#!/bin/bash
#
# Run server and switch to prod environment

echo "APP_ENV=dev
APP_SECRET=090892cefdc0b8c82489231ce79fc975
MAILER_URL=null://localhost" > .env
/d/PHP/v7.2/php bin/console server:run &
sleep 5
echo "APP_ENV=prod
APP_SECRET=090892cefdc0b8c82489231ce79fc975
MAILER_URL=null://localhost" > .env