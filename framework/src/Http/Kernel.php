<?php

namespace Neitsab\Framework\Http;

use Psr\Container\ContainerInterface;

use Neitsab\Framework\Router\RouterInterface;
use Neitsab\Framework\Http\Exceptions\HttpException;


class Kernel
{
	protected RouterInterface $router;

	protected ContainerInterface $container;

	public function __construct(RouterInterface $router, ContainerInterface $container)
	{
		$this->router = $router;
		$this->container = $container;
	}

	public function handle(Request $request): Response
	{
		try {
			[$routeHandler, $vars] = $this->router->dispatch($request, $this->container);
			$response = call_user_func_array($routeHandler, $vars);
		} catch (\Exception $exception) {
			$response = $this->createExceptionResponse($exception);
		}

		return $response;
	}

	public function createExceptionResponse(\Exception $exception): Response
	{
		if ($exception instanceof HttpException) {
			return new Response($exception->getMessage(), $exception->getStatusCode());
		}
		throw $exception;
		// return new Response('Servor Error', Response::HTTP_NOT_OK);
	}
}
