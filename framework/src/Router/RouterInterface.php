<?php

namespace Neitsab\Framework\Router;

use Psr\Container\ContainerInterface;
use FastRoute\RouteCollector;

use Neitsab\Framework\Http\Request;

interface RouterInterface
{
	public function dispatch(Request $request, ContainerInterface $container): array;

	public function loadRoutes(RouteCollector $router): array;
}
