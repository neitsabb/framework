<?php

namespace Modules\Auth\Controllers;

use Modules\Auth\Models\User;
use Neitsab\Framework\Http\Middlewares\Guest;
use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Http\Controller\Controller;
use Neitsab\Framework\Auth\SessionAuthentification;
use Neitsab\Framework\Http\Response\RedirectResponse;

class RegisterController extends Controller
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
				'path' => '/register',
				'method' => 'GET',
				'middlewares' => [
					Guest::class
				]
			],
			'store' => [
				'path' => '/register',
				'method' => 'POST',
			],
		];
	}

	public function index()
	{
		return $this->render('register');
	}

	public function store(): Response
	{
		$errors = $this->request->validate([
			'email' => 'required|email|min:4|unique',
			'username' => 'required|min:4|unique',
			'password' => 'required|min:4',
			'password_confirmation' => 'required|same:password',
		], User::class);

		if ($errors) {
			return $this->render('register', ['errors' => $errors]);
		}

		$user = User::create([
			'email' => $this->request->input('email'),
			'username' => $this->request->input('username'),
			'password' => password_hash($this->request->input('password'), PASSWORD_DEFAULT),
		]);

		$this->auth->login($user);

		return new RedirectResponse('dashboard');
	}
}
