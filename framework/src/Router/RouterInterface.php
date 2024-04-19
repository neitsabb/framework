<?php

namespace Neitsab\Framework\Router;


use FastRoute\RouteCollector;
use Neitsab\Framework\Http\Request\Request;

interface RouterInterface
{
	public function createDispatcher(): mixed;
	public function dispatch(Request $request): array;

	public function loadRoutesFromModules(RouteCollector $router): void;
}
