<?php

namespace Neitsab\Framework\Router;

use FastRoute\RouteCollector;

use Neitsab\Framework\Http\Request;
use Psr\Container\ContainerInterface;

interface RouterInterface
{
	public function dispatch(Request $request, ContainerInterface $container): array;

	public function loadRoutes(RouteCollector $router): array;
}
