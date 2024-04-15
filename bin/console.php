#!/usr/bin/env php

<?php

use Doctrine\DBAL\Types\Type;
use Neitsab\Framework\Database\Type\VarcharType;

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

Type::addType('varchar', VarcharType::class);

$app = require APP_ROOT . '/bootstrap/app.php';

$kernel = $app->get(\Neitsab\Framework\Console\Kernel::class);

$status = $kernel->handle();

exit($status);
