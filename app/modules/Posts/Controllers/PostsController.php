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
			],
			'create' => [
				'path' => '/posts/create',
				'method' => 'GET'
			],
			'store' => [
				'path' => '/posts/create',
				'method' => 'POST'
			],
			'show' => [
				'path' => '/posts/{id:\d+}',
				'method' => 'GET'
			]
		];
	}

	public function index()
	{
		$posts = Post::all();

		$this->request->session->setFlash('name', 'John Doe');

		return $this->render('posts/index');
	}

	public function create()
	{
		return $this->render('posts/create');
	}

	public function store()
	{
		$this->request->session->setFlash('success', 'Post created successfully!');

		return new RedirectResponse('/posts');
	}

	public function show($id)
	{
		return new Response('posts' . $id);
	}
}
