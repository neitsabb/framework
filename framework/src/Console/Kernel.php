<?php

namespace Neitsab\Framework\Console;

use Neitsab\Framework\Console\Command\CommandInterface;
use Psr\Container\ContainerInterface;

final class Kernel
{
	private ContainerInterface $container;

	private Console $console;

	public function __construct(ContainerInterface $container, Console $console)
	{
		$this->container = $container;
		$this->console = $console;
	}

	public function handle(): int
	{
		$this->registerCommands();

		$status = $this->console->run();

		dd($status);

		return $status;
	}

	private function registerCommands()
	{
		$commandFiles = new \DirectoryIterator(__DIR__ . '/Command');

		$namespace = $this->container->get('base_commands_namespace');

		foreach ($commandFiles as $file) {
			if (!$file->isFile()) {
				continue;
			}
			$command = $namespace . pathinfo($file, PATHINFO_FILENAME);
			if (is_subclass_of($command, CommandInterface::class)) {
				$commandName = (new \ReflectionClass($command))
					->getProperty('name')
					->getDefaultValue();

				$this->container->add($commandName, $command);
			}
		}
	}
}
