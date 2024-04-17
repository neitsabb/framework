<?php

namespace Modules\Dashboard\Controllers;

use Neitsab\Framework\Http\Controller\Controller;
use Neitsab\Framework\Http\Middlewares\Authenticate;

class DashboardController extends Controller
{
	public static function routes(): array
	{
		return [
			'index' => [
				'path' => '/dashboard',
				'method' => 'GET',
				'middlewares' => [
					Authenticate::class
				]
			]
		];
	}

	public function index()
	{
		return $this->render('dashboard');
	}
}
