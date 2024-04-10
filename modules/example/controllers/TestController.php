<?php 

namespace Modules\Example\Controllers;

use App\Core\Controller;
use App\Core\Request;

class TestController extends Controller
{
	public static function routes()
	{
		return [
			'index' => [
				'path' => '/test',
				'method' => 'GET'
			],
		];
	}

	public function index(Request $request)
	{
		echo 'TestController not Invokable Works!';
		echo "<br>";
		echo "<pre>";
		var_dump($request);
	}
}