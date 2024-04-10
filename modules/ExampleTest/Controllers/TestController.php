<?php

namespace Modules\ExampleTest\Controllers;

use App\Core\Controller;
use App\Core\Request;

class TestController extends Controller
{
	public static function routes()
	{
		return [
			'index' => [
				'path' => '/',
				'method' => 'GET'
			],
		];
	}

	public function index(Request $request)
	{
		return $this->render('home');
	}
}
