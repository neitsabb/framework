<?php

namespace Modules\Posts\Controllers;

use Modules\Posts\Models\Post;
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
			]
		];
	}

	public function index()
	{
		$posts = Post::all();

		$this->request->session()->setFlash('name', 'John Doe');

		return $this->render();
	}
}
