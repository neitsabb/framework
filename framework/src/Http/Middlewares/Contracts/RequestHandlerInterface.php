<?php

namespace Neitsab\Framework\Http\Middlewares\Contracts;


use Neitsab\Framework\Http\Request;
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
}
