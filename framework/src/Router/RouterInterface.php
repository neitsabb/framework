<?php

namespace Neitsab\Framework\Router;

use FastRoute\RouteCollector;

use Neitsab\Framework\Http\Request;

interface RouterInterface
{
	public function dispatch(Request $request): array;

	public function loadRoutes(RouteCollector $router): void;
}
