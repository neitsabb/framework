<?php

namespace Neitsab\Framework\Console;

use Neitsab\Framework\Core\Application;
use Neitsab\Framework\Console\Command\CommandInterface;

final class Kernel
{
	/**
	 * @var Console $console - the console application
	 */
	private Console $console;

	public function __construct(Console $console)
	{
		$this->console = $console;
	}

	/**
	 * Register all the commands and handle the console application
	 * 
	 * @return int - the status
	 */
	public function handle(): int
	{
		$this->registerCommands();

		$status = $this->console->run();

		dd($status);

		return $status;
	}

	/**
	 * Register all the commands in the /Command directory
	 * 
	 * @return void
	 */
	private function registerCommands()
	{
		$commandFiles = new \DirectoryIterator(__DIR__ . '/Command');

		$namespace = Application::$container->get('base_commands_namespace');

		foreach ($commandFiles as $file) {
			if (!$file->isFile()) {
				continue;
			}
			$command = $namespace . pathinfo($file, PATHINFO_FILENAME);
			if (is_subclass_of($command, CommandInterface::class)) {
				$commandName = (new \ReflectionClass($command))
					->getProperty('name')
					->getDefaultValue();

				Application::$container->add($commandName, $command);
			}
		}
	}
}
