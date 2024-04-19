<?php

namespace Neitsab\Framework\Http\Middlewares\Contracts;

use Neitsab\Framework\Http\Request\Request;
use Neitsab\Framework\Http\Response\Response;

interface MiddlewareInterface
{
	/**
	 * Handle the request.
	 * 
	 * @param Request $request - The request.
	 * @param RequestHandlerInterface $handler - The request handler.
	 * @return Response
	 */
	public function process(Request $request, RequestHandlerInterface $handler): Response;
}
