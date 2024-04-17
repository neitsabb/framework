<?php

namespace Neitsab\Framework\Router;

use FastRoute\Dispatcher;

use FastRoute\RouteCollector;
use Neitsab\Framework\Http\Request;

interface RouterInterface
{
	public function createDispatcher(): mixed;
	public function dispatch(Request $request): array;

	public function loadRoutes(RouteCollector $router): void;
}
