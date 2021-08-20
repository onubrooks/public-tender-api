<?php

/**
 * Heroku set up with queue
 * Note: this file is not publicly visible since it's not in the public folder
 * 
 * Set config var in heroku like this:
  QUEUE_CONNECTION=database

 * To start the worker dyno in case it is not running
  heroku ps:scale worker=1

  * Procfile looks like this: Note: specify the driver you want to use after queue:work

  web: vendor/bin/heroku-php-apache2 public/
  worker: php artisan queue:restart && php artisan queue:work database --tries=3

  * To view messages from the worker
  heroku logs --ps worker

  * To tail messages from the worker
  heroku logs --tail --ps worker
  
 */
echo phpinfo();

// postgres://qemflpwcmhjkxi:055a853c3e54efabe420f7846bcb30008c8835d48768e7c19b68624dace1de34@ec2-54-76-249-45.eu-west-1.compute.amazonaws.com:5432/d15q57jo5qerir
