<?php

namespace Modules\Generator\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Controller;
use Modules\Formatter\Contracts\FormatterInterface;

class PhoneGenerator extends Controller
{
	private FormatterInterface $formatter;

	public function __construct(FormatterInterface $formatter)
	{
		$this->formatter = $formatter;
	}

	public static function routes()
	{
		return [
			'index' => [
				'path' => '/phone',
				'method' => 'GET'
			],
			'generate' => [
				'path' => '/phone',
				'method' => 'POST'
			]
		];
	}

	public function index()
	{
		return $this->render('phone');
	}

	public function generate(Request $request, Response $response)
	{
		$errors = [];

		if (!$request->getBody('country')) {
			$errors['length'] = 'Le pays est requis.';
		}


		if (empty($errors)) {
			$phone = $this->formatter
				->format(value: $request->getBody('country'));

			return $this->render('phone', [
				'phone' => $phone,
				'request' => $request->all()
			]);
		} else return $this->render('phone', [
			'errors' => $errors,
			'request' => $request->all()
		]);
	}
}
