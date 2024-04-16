<?php

namespace Neitsab\Framework\Core;

use Neitsab\Framework\Http\Kernel;
use Neitsab\Framework\Router\Router;
use Psr\Container\ContainerInterface;

use Neitsab\Framework\Console\Console;
use Neitsab\Framework\Session\Session;
use Neitsab\Framework\Template\Template;
use Neitsab\Framework\Database\Connection;
use Neitsab\Framework\Router\RouterInterface;
use Neitsab\Framework\Session\SessionInterface;
use Neitsab\Framework\Template\TemplateFactory;
use Neitsab\Framework\Database\ConnectionFactory;
use Neitsab\Framework\Http\Controller\Controller;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use Neitsab\Framework\Console\Kernel as ConsoleKernel;

final class Application extends \League\Container\Container implements ContainerInterface
{
	static Application $container;
	static string $rootDir;

	static ?Controller $controller;

	public function __construct(string $rootDir)
	{
		self::$rootDir = $rootDir;
		self::$container = $this;
		self::$controller = null;
		parent::__construct();
	}

	public function configure(): void
	{
		$this->delegate(new \League\Container\ReflectionContainer(true));

		$this->configureDatabase();
		$this->configureModules();
		$this->configureRouter();
		$this->configureConsole();
		$this->configureSession();
		$this->configureTheme();
		$this->configureTemplate();
		$this->configureCommands();
	}

	private function configureDatabase(): void
	{
		$this->add(ConnectionFactory::class)
			->addArgument(Config::class);

		$this->addShared(
			Connection::class,
			fn () => $this->get(ConnectionFactory::class)->make()
		);
	}

	private function configureModules(): void
	{
		$this->add(Modules::class)
			->addArgument(
				new StringArgument(
					$this->get(Config::class)->get('modules.path')
				)
			);
	}

	private function configureRouter(): void
	{
		$this->add(RouterInterface::class, Router::class)
			->addArgument(Modules::class);
	}

	private function configureConsole(): void
	{
		$this->add(ConsoleKernel::class)
			->addArgument(Console::class);

		$this->add(Kernel::class)
			->addArgument(RouterInterface::class);
	}

	private function configureSession(): void
	{
		$this->addShared(SessionInterface::class, Session::class)
			->addArgument(
				new StringArgument(
					$this->get(Config::class)->get('session.flash_key')
				)
			);
	}

	private function configureTheme(): void
	{
		$this->add(Theme::class)
			->addArgument(
				new ArrayArgument(
					$this->get(Config::class)->get('themes')
				),
			);
	}

	private function configureTemplate(): void
	{
		$this->add(TemplateFactory::class)
			->addArguments([
				new StringArgument(
					$this->get(Theme::class)->getPath()
				),
				SessionInterface::class,
			]);

		$this->addShared(
			Template::class,
			fn () => $this->get(TemplateFactory::class)->make()
		);
	}

	private function configureCommands(): void
	{
		$this->add(
			'base_commands_namespace',
			new StringArgument(
				'Neitsab\\Framework\\Console\\Command\\'
			)
		);

		$this->add('db:migrate', \Neitsab\Framework\Console\Command\MigrateDatabaseCommand::class)
			->addArguments([
				Connection::class,
				new StringArgument(APP_ROOT . '/app/database/migrations'),
			]);
	}

	public function setController(Controller $controller): void
	{
		self::$controller = $controller;
	}

	public function getController(): Controller
	{
		return self::$controller;
	}
}
