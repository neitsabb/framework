<?php

namespace Neitsab\Framework\Http\Controller;

use Neitsab\Framework\Http\Request\Request;
use Neitsab\Framework\Core\Application;
use Neitsab\Framework\Template\Template;
use Neitsab\Framework\Events\EventDispatcher;
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

	protected function registerEventsListener(string $name, callable $listener): void
	{
		Application::$container->get(EventDispatcher::class)
			->addListener($name, $listener);
	}

	/**
	 * Render a view with the layout.
	 * 
	 * @param string $view - The view to render.
	 * @param array $params - The parameters to pass to the view
	 * @return Response
	 */
	protected function render(array $params = [], ?string $view = null): Response
	{
		$content = Application::$container->get(Template::class)
			->build($params, $this->request, $view);

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
