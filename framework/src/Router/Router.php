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
		[$handler, $vars] = $request->getHandlerWithArgs();

		if (is_array($handler)) {
			$controller = Application::$container->get($handler['controller']);
			if (is_subclass_of($controller, 'Neitsab\Framework\Http\Controller\Controller')) {
				$controller->setRequest($request);
				Application::$container->setController($controller);
			}
			$handler = [$controller, $handler['action']];
		}

		return [$handler, $vars];
	}

	/**
	 * Create the dispatcher
	 * 
	 * @return Dispatcher - The dispatcher
	 */
	public function createDispatcher(): Dispatcher
	{
		return \FastRoute\simpleDispatcher(function (RouteCollector $r) {
			$this->loadRoutes($r);
		});
	}

	/**
	 * Load the routes from the modules
	 * 
	 * @param RouteCollector $router - The router to load the routes into
	 * @return void
	 */
	public function loadRoutes(RouteCollector $router): void
	{
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
									$router->addRoute($route['method'], $route['path'], $controllerNamespace);
								} else {
									$router->addRoute($route['method'], $route['path'], [
										'controller' => $controllerNamespace,
										'action' => $action,
										'middlewares' => $route['middlewares'] ?? []
									]);
								}
							}
						}
					}
				}
			}
		}
	}
}
