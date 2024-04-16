<?php

require_once APP_ROOT . '/vendor/autoload.php';

/**
 * Load the environment variables.
 */
$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(APP_ROOT . '/.env');

/**
 * env() function to get the environment variables.
 */
if (!function_exists('env')) {
	function env(string $key, $default = null)
	{
		return $_ENV[$key] ?? $default;
	}
}

/**
 * Create the container of the application and configure
 * the services and dependencies.
 * 
 * @var \Neitsab\Framework\Core\Application $app
 * @return \Neitsab\Framework\Core\Application
 */
$app = new Neitsab\Framework\Core\Application(
	APP_ROOT
);

$app->configure();

return $app;
