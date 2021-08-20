<?php

/**
 * Heroku set up with queue
 * Note: this file is not publicly visible since it's not in the public folder
 * 
 * Set config var in heroku like this:
  QUEUE_CONNECTION=database

 * To start the worker dyno in case it is not running. 
 * Try running `php artisan storage:link` first for public uploads to be read from the right place
  heroku ps:scale worker=1

 * Procfile looks like this: Note: specify the driver you want to use after queue:work

  web: vendor/bin/heroku-php-apache2 public/
  worker: php artisan queue:restart && php artisan queue:work database --tries=3

 * To view messages from the worker
  heroku logs --ps worker

 * To tail messages from the worker
  heroku logs --tail --ps worker
 */

// to do:
// 1. if processing fails, status is stuck at processing
// 2. find an alternative storage option
// 3. fix search contracts endpoint
// 4. make file names unique for stored files

// post install commands:
// php artisan storage:link
// heroku ps:scale worker=1
