<?php

namespace Neitsab\Framework\Http;


use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Router\RouterInterface;
use Neitsab\Framework\Http\Exceptions\HttpException;
use Neitsab\Framework\Http\Middlewares\Contracts\RequestHandlerInterface;

class Kernel
{
	/**
	 * @var RouterInterface $router - The router instance
	 */
	protected RouterInterface $router;

	/**
	 * @var RequestHandlerInterface $requestHandler - The request handler instance
	 */
	protected RequestHandlerInterface $requestHandler;

	public function __construct(RouterInterface $router, RequestHandlerInterface $requestHandler)
	{
		$this->router = $router;
		$this->requestHandler = $requestHandler;
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
			$response = $this->requestHandler->handle($request);
		} catch (\Exception $exception) {
			$response = $this->createExceptionResponse($exception);
		}

		return $response;
	}

	/**
	 * Terminate the request
	 * 
	 * @param Response $response - The response to terminate
	 * @return void
	 */
	public function terminate(Request $request, Response $response): void
	{
		$request->session()?->clearFlash();
		$request->session()?->setPreviousUrl($request->uri());
	}

	/**
	 * Create an exception response
	 * 
	 * @param \Exception $exception - The exception to create a response for
	 * @return Response - The response
	 */
	private function createExceptionResponse(\Exception $exception): Response
	{
		if ($exception instanceof HttpException) {
			return new Response($exception->getMessage(), $exception->getStatusCode());
		}
		throw $exception;
		// return new Response('Servor Error', Response::HTTP_NOT_OK);
	}
}
