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
	 * resolves a route callback based on the request method and path, invoking the
	 * appropriate controller method or returning a 404 error if not found.
	 * 
	 * @return array|string|null The `resolve()` function returns an array, string, or null value. The
	 * specific return value depends on the logic within the function:
	 * - If the route callback is a string, the function checks if the string is an invokable class or a
	 * controller method. If the string is an invokable class, the function creates an instance of the
	 * class and invokes the `__invoke()` method. If the string is a controller method, the function creates an instance
	 * of the controller class and invokes the specified method.
	 * - If the route callback is a closure, the function invokes the closure.
	 * - If the route callback is not found, the function returns a 404 error.
	 */
	public function resolve(): array|string|null
	{
		$path = $this->request->getPath();
		$method = $this->request->getMethod();
		$callback = $this->routes[$method][$path] ?? false;

		if (!$callback) {
			$this->response->setStatusCode(404);
			return 'Not Found 404';
		}

		if (is_string($callback)) {
			if (method_exists($callback, '__invoke')) {
				$controller = Application::$container->get($callback);
				Application::$app->controller = $controller;
				return $controller($this->request, $this->response);
			} else {
				$callback = explode('@', $callback);
				$controller = Application::$container->get($callback[0]);
				Application::$app->controller = $controller;
				$controller->action = $callback[1];
				if (method_exists($controller, $callback[1])) {
					return $controller->{$callback[1]}($this->request, $this->response);
				} else {
					return Exception::throw('Method not found in controller', 404);
				}
			}
		}

		return $callback($this->request);
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
					if ($componentName !== 'Controllers') continue;

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
