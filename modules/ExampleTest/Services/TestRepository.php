<?php

namespace Modules\ExampleTest\Services;

class TestRepository
{
	public function findAll(): array
	{
		return [
			['id' => 1, 'name' => 'John Doe'],
			['id' => 2, 'name' => 'Jane Doe'],
		];
	}
}
