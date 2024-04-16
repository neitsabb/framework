<?php

namespace Modules\Posts\Controllers;

use Neitsab\Framework\Http\Response\Response;
use Neitsab\Framework\Http\Controller\Controller;
use Neitsab\Framework\Http\Response\RedirectResponse;

class PostsController extends Controller
{
	public static function routes(): array
	{
		return [
			'index' => [
				'path' => '/posts',
				'method' => 'GET'
			],
			'create' => [
				'path' => '/posts/create',
				'method' => 'GET'
			],
			'show' => [
				'path' => '/posts/{id:\d+}',
				'method' => 'GET'
			]
		];
	}

	public function index()
	{
		return $this->render('posts/index');
	}

	public function create()
	{
		return new Response('posts/create');
	}

	public function show($id)
	{
		return new Response('posts' . $id);
	}
}
