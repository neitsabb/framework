<?php

namespace Modules\Example\Controllers;

use App\Core\Controller;
use App\Core\Request;
use Modules\Example\Contracts\TestRepositoryInterface;
use Modules\Example\Services\TestRepository;

class TestController extends Controller
{
	private TestRepository $testRepository;

	public function __construct(TestRepository $testRepository)
	{
		$this->testRepository = $testRepository;
	}
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
		var_dump($this->testRepository->findAll());
	}
}
