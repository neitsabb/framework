<?php

namespace App\Core;

abstract class Controller
{
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
	 * Example:
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
	abstract public static function routes();

	/**
	 * Register a middleware for the controller.
	 * 
	 * @param array $middlewares - The middlewares to register.
	 */
	protected function registerMiddleware(array $middlewares)
	{
		$this->middlewares[] = $middlewares;
	}

	/**
	 * Render a view with the layout.
	 * 
	 * @param string $view - The view to render.
	 * @param array $params - The parameters to pass to the view
	 */
	protected function render(string $view, array $params = [])
	{
		return Application::$app->template->build($view, $params, $this->layout);
	}
}
