<?php

namespace App\Core;

abstract class Controller
{
	public string $layout = 'main';
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

	protected function registerMiddleware(array $middlewares)
	{
		$this->middlewares[] = $middlewares;
	}

	protected function render(string $view, array $params = [])
	{
		return Application::$app->template->build($view, $params, $this->layout);
	}
}
