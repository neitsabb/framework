<?php

namespace App\Contracts;

use App\Core\Request;
use FastRoute\RouteCollector;
use Psr\Container\ContainerInterface;

interface RouterInterface
{
	public function dispatch(Request $request, ContainerInterface $container): array;

	public function loadRoutes(RouteCollector $router): void;
}
