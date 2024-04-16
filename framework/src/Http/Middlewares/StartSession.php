<?php

namespace Neitsab\Framework\Http\Middlewares;

use Neitsab\Framework\Http\Request;
use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Http\Middlewares\Contracts\MiddlewareInterface;
use Neitsab\Framework\Http\Middlewares\Contracts\RequestHandlerInterface;
use Neitsab\Framework\Session\SessionInterface;

class StartSession implements MiddlewareInterface
{
	/**
	 * @var SessionInterface $session - The session instance
	 */
	protected SessionInterface $session;

	public function __construct(SessionInterface $session)
	{
		$this->session = $session;
	}

	public function process(Request $request, RequestHandlerInterface $requestHandler): Response
	{
		$this->session->start();

		$request->setSession($this->session);

		return $requestHandler->handle($request);
	}
}
