<?php

namespace App\Core;

use DI\Container;
use Modules\Example\Services\TestRepository;
use Modules\Example\Contracts\TestRepositoryInterface;

class Services
{
	/**
	 * @var Container $container - The container is responsible for managing class dependencies and performing dependency injection.
	 */
	private Container $container;
	public function __construct()
	{
		$this->container = Application::$app->container;
	}
}
