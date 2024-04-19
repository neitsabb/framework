<?php

namespace Neitsab\Framework\Http\Middlewares\Contracts;


use Neitsab\Framework\Http\Request\Request;
use Neitsab\Framework\Http\Response\Response;

interface RequestHandlerInterface
{
	/**
	 * Handle the request.
	 * 
	 * @param Request $request - The request.
	 * @return Response
	 */
	public function handle(Request $request): Response;

	/**
	 * Inject middlewares at the beginning of the array.
	 * 
	 * @param array $middlewares
	 * @return void
	 */
	public function inject(array $middlewares): void;
}
