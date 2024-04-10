<?php

namespace App\Core;

use App\Core\Application;
use App\Core\Request;
use App\Core\Response;

class Router
{
	/**
	 * @var array $routes
	 */
	public array $routes = [];

	/**
	 * @var Request $request
	 */
	protected Request $request;

	/**
	 * @var Response $response
	 */
	protected Response $response;

	/**
	 * The constructor initializes the request and response objects and loads the routes.
	 * 
	 * @param Request $request - The request object that contains information about the current request.
	 * @param Response $response - The response object that contains methods to set the response status code and redirect users to specified URLs.
	 * @return void
	 */
	public function __construct(Request $request, Response $response)
	{
		$this->request = $request;
		$this->response = $response;

		$this->loadRoutes();
	}

	/**
	 * Resolves a callback based on the request path and method, handling different types of callbacks 
	 * including invokable classes and controller methods.
	 * 
	 * @return mixed - the callback function or method based on the route defined in the routes array. 
	 * If the callback is a string, it checks if it is a callable class or a controller method, 
	 * and then executes it with the current request object. If the callback is not found or is not a valid method, 
	 * it sets the response status code to 404.
	 */
	public function resolve(): ?array
	{
		$path = $this->request->getPath();
		$method = $this->request->getMethod();
		$callback = $this->routes[$method][$path] ?? false;

		if (!$callback) {
			$this->response->setStatusCode(404);
			return $this->response->redirect('/404');
		}

		if (is_string($callback)) {
			if (method_exists($callback, '__invoke')) {
				return (new $callback)($this->request);
			} else {
				$callback = explode('@', $callback);
				$controller = Application::$container->get($callback[0]);
				$controller->action = $callback[1];
				if (method_exists($controller, $callback[1])) {
					return $controller->{$callback[1]}($this->request);
				} else {
					return Exception::throw('Method not found in controller', 404);
				}
			}
		}
	}

	/**
	 * Adds routes to a routing system based on the provided routes and
	 * controller.
	 * 
	 * @param array routes - contains information about the routes to be added. It typically includes details such as the HTTP method (GET, POST, etc.) and the route path.
	 * @param string controller - It can be either a class instance that contains the methods to be invoked when a specific route is accessed.
	 * @return void
	 */
	private function addRoutes(array $routes, string $controller): void
	{
		if (method_exists($controller, '__invoke')) {
			$this->routes[$routes['method']][$routes['path']] = $controller;
		} else {
			foreach ($routes as $callback => $route) {
				$this->routes[$route['method']][$route['path']] = $controller . '@' . $callback;
			}
		}
	}

	/**
	 * Iterates through modules and components to load controller routes.
	 * 
	 * @return void
	 */
	private function loadRoutes(): void
	{
		foreach (Application::$app->modules->all() as $moduleName => $modules) {
			foreach ($modules as $componentName => $component) {
				foreach ($component as $controllerName) {
					// Transformer le nom du contrôleur en namespace
					$controllerNamespace = 'Modules\\' . $moduleName . '\\' . $componentName . '\\' . $controllerName;
					$routes = $this->getControllerRoutes($controllerNamespace);

					$this->addRoutes($routes, $controllerNamespace);
				}
			}
		}
	}

	/**
	 * Retrieves routes defined in a controller class if the class
	 * exists and has a `routes` method.
	 * 
	 * @param string controllerNamespace The `controllerNamespace` parameter in the
	 * `getControllerRoutes` function is a string that represents the namespace of a controller class.
	 * This function checks if the specified controller class exists, and if it does, it uses
	 * reflection to check if the class has a static method named `routes`. If the method
	 * 
	 * @return array An array of routes is being returned.
	 */
	private function getControllerRoutes(string $controllerNamespace): array
	{
		$routes = [];
		if (class_exists($controllerNamespace)) {
			$reflectionClass = new \ReflectionClass($controllerNamespace);
			if ($reflectionClass->hasMethod('routes')) {
				$routes = $controllerNamespace::routes();
			}
		}
		return $routes;
	}
}
