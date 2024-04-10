<?php

namespace App\Core;


use App\Core\Config;
use App\Core\Container;
use App\Core\Modules;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;


class Application
{

	/**
	 * @var string $rootDir - The root directory of the application
	 */
	public static $rootDir;

	/**
	 * @var Application $app - The application instance
	 */
	public static $app;

	/**
	 * @var Container $container - The container is responsible for managing class dependencies and performing dependency injection.
	 */
	public static Container $container;

	/**
	 * @var Config $config - The configuration contains the configuration of the application
	 */
	public Config $config;

	/**
	 * @var Modules $modules - is responsible for loading and organizing modules within a specified directory structure
	 */
	public Modules $modules;

	/**
	 * @var Request $request - handles sanitizing and storing request values, retrieving URL paths, and checking HTTP request methods.
	 */
	public Request $request;

	/**
	 * @var Response $response - provides methods to set HTTP response codes and redirect users to specified URLs in PHP.
	 */
	public Response $response;

	/**
	 * @var Router $router - handles routing requests to appropriate controller actions based on defined routes.
	 */
	public Router $router;

	/**
	 * The constructor initializes various components such as configuration, modules, request, response,
	 * and router in a PHP class.
	 * 
	 * @param string path - The path parameter is a string that represents the root directory of the application.
	 */
	public function __construct(string $path)
	{
		self::$rootDir = $path;
		self::$app = $this;
		self::$container = new Container();

		// Load the configuration
		$this->config = new Config();

		// Load the modules
		$this->modules = new Modules();

		$this->request = new Request();
		$this->response = new Response();
		$this->router = new Router($this->request, $this->response);
	}

	/**
	 * The run method is responsible for running the application and resolving the router.
	 * @return void
	 */
	public function run(): void
	{
		echo $this->router->resolve();
	}

	/**
	 * The getRoutes method returns the routes defined in the router.
	 * @return array The routes defined in the router.
	 */
	public function getRoutes(): array
	{
		return $this->router->routes;
	}
}
