<?php

namespace Neitsab\Framework\Http\Middlewares;

use Neitsab\Framework\Http\Request;
use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Http\Middlewares\Contracts\MiddlewareInterface;
use Neitsab\Framework\Http\Middlewares\Contracts\RequestHandlerInterface;

class Authenticate implements MiddlewareInterface
{
	private bool $authenticated = true;

	public function process(Request $request, RequestHandlerInterface $handler): Response
	{
		if (!$this->authenticated)
			return new Response('Unauthorized', 401);

		return $handler->handle($request);
	}
}
