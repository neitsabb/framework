<?php

namespace Neitsab\Framework\Http\Middlewares;

use FastRoute\Dispatcher;
use Neitsab\Framework\Http\Request;
use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Router\RouterInterface;
use Neitsab\Framework\Http\Exceptions\HttpException;
use Neitsab\Framework\Http\Exceptions\HttpRequestMethodException;
use Neitsab\Framework\Http\Middlewares\Contracts\MiddlewareInterface;
use Neitsab\Framework\Http\Middlewares\Contracts\RequestHandlerInterface;

class ExtractRouteInfo implements MiddlewareInterface
{
	private RouterInterface $router;

	public function __construct(RouterInterface $router)
	{
		$this->router = $router;
	}
	public function process(Request $request, RequestHandlerInterface $handler): Response
	{
		$routeInfo = $this->router->createDispatcher()
			->dispatch(
				$request->method(),
				$request->uri()
			);

		switch ($routeInfo[0]) {
			case Dispatcher::FOUND:
				$request->setHandlerWithArgs($routeInfo[1], $routeInfo[2]);
				if (is_array($routeInfo[1]) && isset($routeInfo[1]['middlewares'])) {
					$handler->inject($routeInfo[1]['middlewares']);
				}
				break;
			case Dispatcher::METHOD_NOT_ALLOWED:
				$allowedMethods = implode(', ', $routeInfo[1]);
				$e = new HttpRequestMethodException("The allowed methods are $allowedMethods");
				$e->setStatusCode(405);
			default:
				$e = new HttpException('Not found');
				$e->setStatusCode(404);
				throw $e;
		}

		return $handler->handle($request);
	}
}
