<?php

declare(strict_types=1);

define('APP_ROOT', $_ENV['APP_URL'] ?? dirname(__DIR__));

/**
 * The entry point of the application
 * 
 * @package basti/neitsabKit
 *  
 * @version 0.0.1
 * @license MIT
 * @author Neitsab
 */

$app = require_once APP_ROOT . '/bootstrap/app.php';

$kernel = $app->get(Neitsab\Framework\Http\Kernel::class);

$response = $kernel->handle(
	$request = Neitsab\Framework\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
