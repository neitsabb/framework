<?php

namespace Modules\Auth\Controllers;

use Neitsab\Framework\Auth\SessionAuthentification;
use Neitsab\Framework\Http\Controller\Controller;
use Neitsab\Framework\Http\Middlewares\Authenticate;
use Neitsab\Framework\Http\Response\RedirectResponse;

class LogoutController extends Controller
{
	private SessionAuthentification $auth;

	public function __construct(SessionAuthentification $auth)
	{
		$this->auth = $auth;
	}

	public static function routes(): array
	{
		return [
			'logout' => [
				'path' => '/logout',
				'method' => 'GET',
				'middlewares' => [
					Authenticate::class
				]
			],
		];
	}

	public function logout(): RedirectResponse
	{
		$this->auth->logout();

		return new RedirectResponse('login');
	}
}
