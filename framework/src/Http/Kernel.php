<?php

namespace Neitsab\Framework\Http;


use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Router\RouterInterface;
use Neitsab\Framework\Http\Exceptions\HttpException;


class Kernel
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
	 * Handle the request
	 * 
	 * @param Request $request - The request to handle
	 * @return Response - The response
	 */
	public function handle(Request $request): Response
	{
		try {
			[$routeHandler, $vars] = $this->router->dispatch($request);
			$response = call_user_func_array($routeHandler, $vars);
		} catch (\Exception $exception) {
			$response = $this->createExceptionResponse($exception);
		}

		return $response;
	}

	/**
	 * Create an exception response
	 * 
	 * @param \Exception $exception - The exception to create a response for
	 * @return Response - The response
	 */
	public function createExceptionResponse(\Exception $exception): Response
	{
		if ($exception instanceof HttpException) {
			return new Response($exception->getMessage(), $exception->getStatusCode());
		}
		throw $exception;
		// return new Response('Servor Error', Response::HTTP_NOT_OK);
	}
}
