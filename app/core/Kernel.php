<?php

namespace App\Core;

use App\Core\Response;

class Kernel
{

	public function handle(Request $request): Response
	{
		return new Response('Hello, World!');
	}
}
