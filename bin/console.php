#!/usr/bin/env php

<?php


define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$app = require APP_ROOT . '/bootstrap/app.php';

$console = $app->get(App\Core\Console::class);

$status = $console->handle();

exit($status);
