<?php

namespace Neitsab\Framework\Http\Middlewares;

use Neitsab\Framework\Http\Request\Request;
use Neitsab\Framework\Session\Session;
use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Session\SessionInterface;
use Neitsab\Framework\Http\Middlewares\Contracts\MiddlewareInterface;
use Neitsab\Framework\Http\Middlewares\Contracts\RequestHandlerInterface;
use Neitsab\Framework\Http\Response\RedirectResponse;

class Guest implements MiddlewareInterface
{
	private bool $authenticated;

	public function __construct(SessionInterface $session)
	{
		$this->authenticated = $session->has(Session::AUTH_KEY);
	}

	public function process(Request $request, RequestHandlerInterface $handler): Response
	{
		if ($this->authenticated)
			return new RedirectResponse($request->session()->getPreviousUrl());

		return $handler->handle($request);
	}
}
