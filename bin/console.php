<?php

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(APP_ROOT . '/.env');

if (!function_exists('env')) {
	function env(string $key, $default = null)
	{
		return $_ENV[$key] ?? $default;
	}
}

$app = require APP_ROOT . '/bootstrap/app.php';

$kernel = $app->get(\Neitsab\Framework\Console\Kernel::class);

$status = $kernel->handle();

exit($status);
