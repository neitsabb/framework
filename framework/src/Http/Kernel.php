<?php

namespace Neitsab\Framework\Http;

use Neitsab\Framework\Http\Request\Request;
use Neitsab\Framework\Events\EventDispatcher;
use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Router\RouterInterface;
use Neitsab\Framework\Http\Events\ResponseEvent;
use Neitsab\Framework\Http\Exceptions\HttpException;
use Neitsab\Framework\Http\Middlewares\Contracts\RequestHandlerInterface;

class Kernel
{
	/**
	 * @var RequestHandlerInterface $requestHandler - The request handler instance
	 */
	protected RequestHandlerInterface $requestHandler;

	/**
	 * @var EventDispatcher $eventDispatcher - The event dispatcher instance
	 */
	protected EventDispatcher $eventDispatcher;

	public function __construct(
		RequestHandlerInterface $requestHandler,
		EventDispatcher $eventDispatcher
	) {
		$this->requestHandler = $requestHandler;
		$this->eventDispatcher = $eventDispatcher;
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

		$this->eventDispatcher->dispatch(new ResponseEvent($request, $response));

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
