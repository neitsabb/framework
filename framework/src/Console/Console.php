<?php

namespace Neitsab\Framework\Console;

use Neitsab\Framework\Console\Exception\ConsoleException;
use Neitsab\Framework\Core\Application;

class Console
{
	/**
	 * Run the console application
	 * 
	 * @return int - the status
	 */
	public function run(): int
	{
		$argv = $_SERVER['argv'];

		$commandName = $argv[1] ?? 'help';

		if ($commandName === 'help') {
			throw new ConsoleException('Help command not implemented yet');
		}

		$args = array_slice($argv, 2);
		$options = $this->parseOptions($args);

		if (!Application::$container->has($commandName)) {
			throw new ConsoleException("Command $commandName not found");
		}

		$command = Application::$container->get($commandName);

		$status = $command->execute($options);

		return $status;
	}

	/**
	 * Parse the options from the command line arguments
	 * 
	 * @example --option=value --option2
	 * 
	 * @param array $args - the command line arguments
	 * @return array - the options
	 */
	private function parseOptions(array $args): array
	{
		$options = [];

		foreach ($args as $arg) {
			if (str_starts_with($arg, '--')) {
				$option = explode('=', substr($arg, 2));
				$options[$option[0]] = $option[1] ?? true;
			}
		}

		return $options;
	}
}
