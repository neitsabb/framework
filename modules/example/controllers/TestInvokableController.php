<?php 

namespace Modules\Example\Controllers;

use App\Core\Controller;
use App\Core\Request;

class TestInvokableController extends Controller
{
	public static function routes()
	{
		return [
			'path' => '/',
			'method' => 'GET'
		];
	}

	public function __invoke(Request $request)
	{
		echo 'TestController Invokable Works!';
		echo "<br>";
		echo "<pre>";
		var_dump($request);
	}
}