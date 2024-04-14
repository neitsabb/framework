<?php

namespace Modules\Generator\Controllers;

use Neitsab\Framework\Http\Request;
use Neitsab\Framework\Http\Response;
use Neitsab\Framework\Http\Controller\Controller;

use Modules\Generator\Services\PasswordFormatter;


class PasswordGenerator extends Controller
{
	private PasswordFormatter $formatter;

	public function __construct(PasswordFormatter $formatter)
	{
		$this->formatter = $formatter;
	}

	public static function routes()
	{
		return [
			'index' => [
				'path' => '/',
				'method' => 'GET'
			],
			'generate' => [
				'path' => '/',
				'method' => 'POST'
			]
		];
	}



	public function index()
	{

		return new Response('coucou');
	}

	public function generate(Request $request, Response $response)
	{
		return 'Hello! ';
	}
}
