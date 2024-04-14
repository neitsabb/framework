<?php

namespace Modules\Generator\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Controller;
use Modules\Formatter\Contracts\FormatterInterface;

class PasswordGenerator extends Controller
{
	private FormatterInterface $formatter;

	public array $views;

	public function __construct(FormatterInterface $formatter)
	{
		$this->formatter = $formatter;

		$this->views = [
			'generator'
		];
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
		return $this->render('generator');
	}

	public function generate(Request $request, Response $response)
	{
		$errors = [];

		if (!$request->getBody('length')) {
			$errors['length'] = 'La longueur est requise.';
		}

		if (!$request->getBody('lowercase') && !$request->getBody('uppercase') && !$request->getBody('digits') && !$request->getBody('specialchars')) {
			$errors['options'] = 'Au moins une option doit être cochée.';
		}

		if (empty($errors)) {
			$length = $request->getBody('length');
			$lowercase = $request->getBody('lowercase');
			$uppercase = $request->getBody('uppercase');
			$digits = $request->getBody('digits');
			$specialchars = $request->getBody('specialchars');

			$password = $this->formatter
				->format(value: [
					'length' => $length,
					'lowercase' => $lowercase,
					'uppercase' => $uppercase,
					'digits' => $digits,
					'specialchars' => $specialchars
				]);

			return $this->render('generator', [
				'password' => $password,
				'request' => $request->all()
			]);
		} else return $this->render('generator', [
			'errors' => $errors,
			'request' => $request->all()
		]);
	}
}
