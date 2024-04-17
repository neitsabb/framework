<?php

namespace Neitsab\Framework\Http\Middlewares;

use Neitsab\Framework\Core\Application;
use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Http\Exceptions\RequestHandlerException;
use Neitsab\Framework\Http\Middlewares\Contracts\RequestHandlerInterface;
use Neitsab\Framework\Http\Request;

class RequestHandler implements RequestHandlerInterface
{
	private array $middlewares = [
		StartSession::class,
		ExtractRouteInfo::class,
		RouterDispatcher::class,
	];

	public function handle(Request $request): Response
	{
		$this->checkMiddlewares();

		$middleware = array_shift($this->middlewares);

		$response = Application::$container->get($middleware)
			->process($request, $this);

		return $response;
	}

	private function checkMiddlewares()
	{
		if (empty($this->middlewares)) {
			throw new RequestHandlerException('No middlewares found.');
		}
	}

	/**
	 * Inject middlewares at the beginning of the array.
	 * 
	 * @param array $middlewares
	 * @return void
	 */
	public function inject(array $middlewares): void
	{
		// Inject the middlewares at the beginning of the array
		array_splice(
			$this->middlewares,
			0,
			0,
			$middlewares
		);
	}
}
