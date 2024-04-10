<?php

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


// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
$dotenv->load();

// Require the helpers
require_once APP_ROOT . '/app/helpers/utils.php';

// Set the error reporting level
if (env('APP_DEBUG')) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

// Bootstrap the application
$app = new App\Core\Application(APP_ROOT);

// Run the application
try {
	$app->run();
} catch (Exception $e) {
	echo "Une erreur s'est produite: " . $e->getMessage();
}
