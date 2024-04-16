<?php

namespace Modules\Auth\Controllers;

use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Http\Controller\Controller;

class LoginController extends Controller
{
	public static function routes(): array
	{
		return [
			'index' => [
				'path' => '/login',
				'method' => 'GET',
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

		dd($errors);

		return new Response('Redirect to the dashboard');
		// Handle the login form submission
		// Validate the form data
		// Authenticate the user
		// Redirect the user to the dashboard
	}
}
