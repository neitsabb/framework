<?php

namespace Neitsab\Framework\Administration\Controllers;

use Neitsab\Framework\Http\Controller\Controller;
use Neitsab\Framework\Http\Response\Response;

class AdministrationController extends Controller
{
	public string $layout = 'admin';

	public static function routes(): array
	{
		return [
			'dashboard' => [
				'path' => '/admin',
				'method' => 'GET',
			],
		];
	}

	public function dashboard()
	{

		return $this->render(view: 'dashboard');
	}
}
