<?php

namespace Neitsab\Framework\Core;


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
	 * @var Theme $theme - handles loading the active theme and rendering views using the theme.
	 */
	public Theme $theme;

	/**
	 * @var Template $template - handles rendering views using the active theme.
	 */
	public Template $template;

	/**
	 * @var FileCache $cache - handles caching data to improve the performance of the application.
	 */
	public FileCache $cache;

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
	 * @var Controller $controller - The controller is responsible for handling user requests and producing responses.
	 */
	public Controller $controller;

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

		// Load the theme
		$this->theme = new Theme();
		$this->template = new Template();

		// Load the cache 
		$this->cache = new FileCache();
		// dd($this->cache);

		// Load the request, response, and router
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
	 * Sets the controller property of the application.
	 * 
	 * @param Controller $controller - The controller object that will be set as the controller property.
	 */
	public function setController(Controller $controller): void
	{
		$this->controller = $controller;
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
