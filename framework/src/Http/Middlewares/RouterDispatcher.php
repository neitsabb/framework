<?php

namespace Neitsab\Framework\Http\Middlewares;

use Neitsab\Framework\Http\Request;
use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Router\RouterInterface;
use Neitsab\Framework\Http\Middlewares\Contracts\MiddlewareInterface;
use Neitsab\Framework\Http\Middlewares\Contracts\RequestHandlerInterface;

class RouterDispatcher implements MiddlewareInterface
{
	/**
	 * @var RouterInterface $router - The router instance
	 */
	protected RouterInterface $router;

	public function __construct(RouterInterface $router)
	{
		$this->router = $router;
	}

	/**
	 * Process the request by dispatching the request in the router
	 * 
	 * @param Request $request - The request to process
	 * @param RequestHandlerInterface $handler - The request handler
	 * @return Response - The response
	 */
	public function process(Request $request, RequestHandlerInterface $handler): Response
	{
		[$routeHandler, $vars] = $this->router->dispatch($request);

		$response = call_user_func_array($routeHandler, $vars);

		return $response;
	}
}
