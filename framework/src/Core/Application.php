<?php

namespace Neitsab\Framework\Core;

use Psr\Container\ContainerInterface;

use Neitsab\Framework\Http\Kernel;
use Neitsab\Framework\Router\Router;
use Neitsab\Framework\Console\Console;
use Neitsab\Framework\Router\RouterInterface;
use Neitsab\Framework\Database\ConnectionFactory;
use Neitsab\Framework\Http\Controller\Controller;
use Neitsab\Framework\Console\Kernel as ConsoleKernel;

final class Application extends \League\Container\Container implements ContainerInterface
{

	static Application $app;

	public function __construct()
	{
		self::$app = $this;
		parent::__construct();
	}

	public function configure()
	{
		$container = $this;

		$this->delegate(new \League\Container\ReflectionContainer(true));

		$this->add(Config::class)
			->addArgument(APP_ROOT);

		$this->add(Modules::class)->addArguments([
			APP_ROOT,
			Config::class
		]);

		$this->add(
			RouterInterface::class,
			Router::class
		)->addArguments([Config::class, Modules::class]);

		$this->add(Console::class)
			->addArgument($container);

		$this->add(Kernel::class)
			->addArgument(RouterInterface::class)
			->addArgument($container);

		$this->add(ConsoleKernel::class)
			->addArguments([$container, Console::class]);

		$this->add(Controller::class);

		$this->inflector(Controller::class)
			->invokeMethod('setContainer', [$container]);

		$this->add(ConnectionFactory::class)
			->addArgument(Config::class);

		$this->addShared(
			\Doctrine\DBAL\Connection::class,
			function () use ($container) {
				return $container->get(ConnectionFactory::class)->make();
			}
		);
		$this->add(
			'base_commands_namespace',
			new \League\Container\Argument\Literal\StringArgument(
				'Neitsab\\Framework\\Console\\Command\\'
			)
		);

		$this->add(
			'db:migrate',
			\Neitsab\Framework\Console\Command\MigrateDatabaseCommand::class
		)->addArgument(\Doctrine\DBAL\Connection::class);
	}
}
