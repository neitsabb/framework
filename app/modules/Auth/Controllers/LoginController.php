<?php

namespace Modules\Auth\Controllers;

use Neitsab\Framework\Auth\SessionAuthentification;
use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Http\Controller\Controller;
use Neitsab\Framework\Http\Middlewares\Guest;
use Neitsab\Framework\Http\Response\RedirectResponse;

class LoginController extends Controller
{
	public string $layout = 'guest';

	private SessionAuthentification $auth;

	public function __construct(SessionAuthentification $auth)
	{
		$this->auth = $auth;
	}
	public static function routes(): array
	{
		return [
			'index' => [
				'path' => '/login',
				'method' => 'GET',
				'middlewares' => [
					Guest::class
				]
			],
			'store' => [
				'path' => '/login',
				'method' => 'POST',
			]
		];
	}

	public function index()
	{
		return $this->render(
			params: [
				'name' => 'test'
			],
			view: 'login'
		);
	}
}
