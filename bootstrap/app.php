<?php

use App\Core\ConnectionFactory;
use App\Core\Controller;
use App\Core\Kernel;
use App\Core\Router;
use App\Core\Modules;
use League\Container\Container;
use App\Contracts\RouterInterface;
use App\Core\Config;

$container = new Container();
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
)->addArgument(Modules::class);

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
