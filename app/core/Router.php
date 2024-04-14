<?php

namespace App\Core;

use App\Contracts\RouterInterface;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use App\Exceptions\Http\HttpException;
use App\Exceptions\Http\HttpRequestMethodException;
use Psr\Container\ContainerInterface;

class Router implements RouterInterface
{

	/**
	 * @var Modules $modules
	 */
	private Modules $modules;

	public function __construct(Modules $modules)
	{
		$this->modules = $modules;
	}

	public function dispatch(Request $request, ContainerInterface $container): array
	{
		$routeInfo = $this->extractRouteInfo($request);

		[$handler, $vars] = $routeInfo;

		if (is_array($handler)) {
			[$controllerId, $action] = $handler;
			$controller = $container->get($controllerId);
			$handler = [$controller, $action];
		}

		return [$handler, $vars];
	}

	private function extractRouteInfo(Request $request)
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

	private function createDispatcher(): Dispatcher
	{
		return \FastRoute\simpleDispatcher(function (RouteCollector $r) {
			$this->loadRoutes($r);
		});
	}

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
									// Si le contrôleur est invocable, utilisez simplement la route spécifiée
									$router->addRoute($route['method'], $route['path'], $controllerNamespace);
								} else {
									// Si le contrôleur n'est pas invocable, utilisez la méthode spécifiée comme action
									$router->addRoute($route['method'], $route['path'], [$controllerNamespace, $action]);
								}
							}
						}
					}
				}
			}
		}
	}
}
