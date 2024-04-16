<?php

namespace Modules\Auth\Controllers;

use Neitsab\Framework\Http\Controller\Controller;

class RegisterController extends Controller
{
	public static function routes(): array
	{
		return [
			'index' => [
				'path' => '/register',
				'method' => 'GET',
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

	public function store()
	{
		// Handle the registration form submission
		// Validate the form data
		// Create a new user
		// Redirect the user to the login page
	}
}
