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
				'path' => '/generator',
				'method' => 'GET'
			],
		];
	}

	public function index(Request $request)
	{
		echo 'TestController not Invokable Works!';
		echo "<br>";
	}
}
