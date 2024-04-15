<?php

declare(strict_types=1);

/**
 * The entry point of the application
 * 
 * @package basti/neitsabKit
 *  
 * @version 0.0.1
 * @license MIT
 * @author Neitsab
 */

define('APP_ROOT', $_ENV['APP_URL'] ?? dirname(__DIR__));

require_once APP_ROOT . '/vendor/autoload.php';

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(APP_ROOT . '/.env');

if (!function_exists('env')) {
	function env(string $key, $default = null)
	{
		return $_ENV[$key] ?? $default;
	}
}

$app = require_once APP_ROOT . '/bootstrap/app.php';

$kernel = $app->get(Neitsab\Framework\Http\Kernel::class);

$response = $kernel->handle(
	Neitsab\Framework\Http\Request::capture()
);

$response->send();
