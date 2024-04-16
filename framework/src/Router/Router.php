<?php

namespace Neitsab\Framework\Router;


use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Neitsab\Framework\Core\Application;

use Neitsab\Framework\Core\Modules;
use Neitsab\Framework\Http\Request;
use Neitsab\Framework\Router\RouterInterface;
use Neitsab\Framework\Http\Exceptions\HttpException;
use Neitsab\Framework\Http\Exceptions\HttpRequestMethodException;

class Router implements RouterInterface
{

	/** 
	 * @var Modules $modules
	 */
	private Modules $modules;

	/**
	 * @var array $routes
	 */
	private array $routes;

	public function __construct(Modules $modules)
	{
		$this->modules = $modules;
	}

	/**
	 * Dispatch the request
	 * 
	 * @param Request $request - The request to dispatch
	 * @return array - The handler and the vars
	 */
	public function dispatch(Request $request): array
	{
		$routeInfo = $this->extractRouteInfo($request);

		[$handler, $vars] = $routeInfo;

		if (is_array($handler)) {
			[$controllerId, $action] = $handler;
			$controller = Application::$container->get($controllerId);
			if (is_subclass_of($controller, 'Neitsab\Framework\Http\Controller\Controller')) {
				$controller->setRequest($request);
				Application::$container->setController($controller);
			}
			$handler = [$controller, $action];
		}

		return [$handler, $vars];
	}

	/**
	 * Extract the route info
	 * 
	 * @param Request $request - The request to extract the route info from
	 * @return array - The route info
	 */
	private function extractRouteInfo(Request $request): array
	{
		$routeInfo = $this->createDispatcher()
			->dispatch(
				$request->method(),
				$request->uri()
			);

		switch ($routeInfo[0]) {
			case Dispatcher::FOUND:
				return [$routeInfo[1], $routeInfo[2]]; // routeHandler, vars
			case Dispatcher::METHOD_NOT_ALLOWED:
				$allowedMethods = implode(', ', $routeInfo[1]);
				$e = new HttpRequestMethodException("The allowed methods are $allowedMethods");
				$e->setStatusCode(405);
			default:
				$e = new HttpException('Not found');
				$e->setStatusCode(404);
				throw $e;
		}
	}

	/**
	 * Create the dispatcher
	 * 
	 * @return Dispatcher - The dispatcher
	 */
	private function createDispatcher(): Dispatcher
	{
		return \FastRoute\simpleDispatcher(function (RouteCollector $r) {
			$this->routes = $this->loadRoutes($r);
		});
	}

	/**
	 * Load the routes from the modules
	 * 
	 * @param RouteCollector $router - The router to load the routes into
	 * @return void
	 */
	public function loadRoutes(RouteCollector $router): array
	{
		$loadedRoutes = [];
		foreach ($this->modules->all() as $moduleName => $modules) {
			foreach ($modules as $componentName => $component) {
				foreach ($component as $controllerName) {
					if ($componentName !== 'Controllers') {
						continue;
					}

					$controllerNamespace = 'Modules\\' . $moduleName . '\\' . $componentName . '\\' . $controllerName;

					if (class_exists($controllerNamespace)) {

						$reflectionClass = new \ReflectionClass($controllerNamespace);
						if ($reflectionClass->hasMethod('routes')) {
							$routes = $controllerNamespace::routes();
							foreach ($routes as $action => $route) {
								if ($reflectionClass->hasMethod('__invoke')) {
									$loadedRoutes[$route['method']][$route['path']] = $controllerNamespace;

									$router->addRoute($route['method'], $route['path'], $controllerNamespace);
								} else {
									$loadedRoutes[$route['method']][$route['path']] = [$controllerNamespace, $action];

									// Si le contrôleur n'est pas invocable, utilisez la méthode spécifiée comme action
									$router->addRoute($route['method'], $route['path'], [$controllerNamespace, $action]);
								}
							}
						}
					}
				}
			}
		}

		return $loadedRoutes;
	}
}
