<?php

use Neitsab\Framework\Core\Config;
use Neitsab\Framework\Http\Kernel;
use Neitsab\Framework\Core\Modules;
use Neitsab\Framework\Router\Router;
use Neitsab\Framework\Router\RouterInterface;
use Neitsab\Framework\Database\ConnectionFactory;
use Neitsab\Framework\Http\Controller\Controller;

/**
 * Create the container of the application
 */
$container = new League\Container\Container();
$container->delegate(new League\Container\ReflectionContainer(true));

/**
 * Application Services
 */
$container->add(Config::class)
	->addArgument(APP_ROOT);

$container->add(Modules::class)->addArguments([
	APP_ROOT,
	Config::class
]);

$container->add(
	RouterInterface::class,
	Router::class
)->addArguments([Config::class, Modules::class]);

$container->add(Kernel::class)
	->addArgument(RouterInterface::class)
	->addArgument($container);

$container->add(Controller::class);

$container->inflector(Controller::class)
	->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)
	->addArguments([Config::class]);

$container->addShared(
	\Doctrine\DBAL\Connection::class,
	function () use ($container) {
		return $container->get(ConnectionFactory::class)->make();
	}
);

return $container;
