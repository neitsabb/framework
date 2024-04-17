<?php

namespace Modules\Auth\Controllers;

use Neitsab\Framework\Auth\SessionAuthentification;
use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Http\Controller\Controller;
use Neitsab\Framework\Http\Middlewares\Guest;
use Neitsab\Framework\Http\Response\RedirectResponse;

class LoginController extends Controller
{
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
		return $this->render('login');
	}

	public function store(): Response
	{
		$errors = $this->request->validate([
			'email' => 'required|email',
			'password' => 'required',
		]);

		$isAuth = $this->auth->authenticate(
			$this->request->input('email'),
			$this->request->input('password')
		);

		if (!$isAuth) {
			$errors['auth'] = 'Invalid credentials';
			return $this->render('login', ['errors' => $errors]);
		}

		return new RedirectResponse('dashboard');
	}
}
