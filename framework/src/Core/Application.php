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
	/**
	 * @var Application $container - the container instance
	 */
	static Application $container;

	/**
	 * @var string $rootDir - the root directory
	 */
	static string $rootDir;

	/**
	 * @var Controller $controller - the controller instance
	 */
	static ?Controller $controller;

	public function __construct(string $rootDir)
	{
		self::$rootDir = $rootDir;
		self::$container = $this;
		self::$controller = null;

		parent::__construct();
	}

	/**
	 * Configure the application by adding services to the container
	 * 
	 * @return void
	 */
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

	/**
	 * Configure the database service
	 * 
	 * @return void
	 */
	private function configureDatabase(): void
	{
		$this->add(ConnectionFactory::class)
			->addArgument(Config::class);

		$this->addShared(
			Connection::class,
			fn () => $this->get(ConnectionFactory::class)->make()
		);
	}

	/**
	 * Configure the module service
	 * 
	 * @return void
	 */
	private function configureModules(): void
	{
		$this->add(Modules::class)
			->addArgument(
				new StringArgument(
					$this->get(Config::class)->get('modules.path')
				)
			);
	}

	/**
	 * Configure the router service
	 * 
	 * @return void
	 */
	private function configureRouter(): void
	{
		$this->add(RouterInterface::class, Router::class)
			->addArgument(Modules::class);
	}

	/**
	 * Configure the console service
	 * 
	 * @return void
	 */
	private function configureConsole(): void
	{
		$this->add(ConsoleKernel::class)
			->addArgument(Console::class);

		$this->add(Kernel::class)
			->addArgument(RouterInterface::class);
	}

	/**
	 * Configure the session service
	 * 
	 * @return void
	 */
	private function configureSession(): void
	{
		$this->addShared(SessionInterface::class, Session::class)
			->addArgument(
				new StringArgument(
					$this->get(Config::class)->get('session.flash_key')
				)
			);
	}

	/**
	 * Configure the theme service
	 * 
	 * @return void
	 */
	private function configureTheme(): void
	{
		$this->add(Theme::class)
			->addArgument(
				new ArrayArgument(
					$this->get(Config::class)->get('themes')
				),
			);
	}

	/**
	 * Configure the template service
	 * 
	 * @return void
	 */
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

	/**
	 * Configure the console commands
	 * 
	 * @return void
	 */
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

	/**
	 * Set the controller application instance
	 * 
	 * @param Controller $controller - the controller instance
	 * @return void
	 */
	public function setController(Controller $controller): void
	{
		self::$controller = $controller;
	}

	/**	
	 * Get the controller application instance
	 * 
	 * @return Controller
	 */
	public function getController(): Controller
	{
		return self::$controller;
	}
}
