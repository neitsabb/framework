<?php

namespace Modules\Example\Services;

use Modules\Example\Contracts\TestRepositoryInterface;

class TestRepository implements TestRepositoryInterface
{
	public function findAll(): array
	{
		return [
			['id' => 1, 'name' => 'John Doe'],
			['id' => 2, 'name' => 'Jane Doe'],
		];
	}
}
