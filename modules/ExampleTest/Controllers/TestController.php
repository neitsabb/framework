<?php

namespace Modules\ExampleTest\Controllers;

use App\Core\Controller;
use App\Core\Request;

use Modules\ExampleTest\Services\TestRepository;

class TestController extends Controller
{
	/**
	 * @var TestRepository $testRepository
	 */
	private TestRepository $testRepository;

	public function __construct(TestRepository $testRepository)
	{
		$this->testRepository = $testRepository;
	}

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
		return $this->render('home', [
			'tests' => $this->testRepository->findAll()
		]);
	}
}
