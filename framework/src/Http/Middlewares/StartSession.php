<?php

namespace Neitsab\Framework\Http\Middlewares;

use Neitsab\Framework\Core\Application;
use Neitsab\Framework\Http\Request\Request;
use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Session\SessionInterface;
use Neitsab\Framework\Http\Middlewares\Contracts\MiddlewareInterface;
use Neitsab\Framework\Http\Middlewares\Contracts\RequestHandlerInterface;

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
		if (!str_starts_with($request->uri(), Application::$config->get('app.api_prefix'))) {
			$this->session->start();
		}

		$request->setSession($this->session);

		return $requestHandler->handle($request);
	}
}
