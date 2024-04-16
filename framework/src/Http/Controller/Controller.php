<?php

namespace Neitsab\Framework\Http\Controller;

use Neitsab\Framework\Http\Request;
use Neitsab\Framework\Core\Application;
use Neitsab\Framework\Template\Template;
use Neitsab\Framework\Http\Response\Response;

abstract class Controller
{
	public Request $request;

	/**
	 * @var string $layout - The layout to use for the controller.
	 */
	public string $layout = 'main';

	/**
	 * @var array $middlewares - The middlewares to use for the controller.
	 */
	public array $middlewares = [];

	/**
	 * Add routes to the controller
	 * @return array
	 * 
	 * @example
	 * 
	 * If you have invokable controller, you can return an array with the following structure:
	 * return [
	 * 	'path' => '/',
	 * 	'method' => 'GET'
	 * ]
	 * 
	 * Else you can return an array with the following structure:
	 * 
	 * return [
	 * 	'callback' => 'methodName',
	 * 	'path' => '/',
	 * 	'method' => 'GET'
	 * ];
	 */
	abstract public static function routes(): array;

	/**
	 * Register a middleware for the controller.
	 * 
	 * @param array $middlewares - The middlewares to register.
	 * @return void
	 */
	protected function registerMiddleware(array $middlewares): void
	{
		$this->middlewares[] = $middlewares;
	}

	/**
	 * Render a view with the layout.
	 * 
	 * @param string $view - The view to render.
	 * @param array $params - The parameters to pass to the view
	 * @return Response
	 */
	protected function render(string $view, array $params = []): Response
	{
		$content = Application::$container->get(Template::class)->build($view, $params, $this->layout);

		$response ??= new Response();

		$response->setContent($content);

		return $response;
	}

	/**
	 * Set the request object.
	 * 
	 * @param Request $request - The request object.
	 * @return void
	 */
	public function setRequest(Request $request): void
	{
		$this->request = $request;
	}
}
