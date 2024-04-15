<?php

namespace Neitsab\Framework\Console;

use Psr\Container\ContainerInterface;

use Neitsab\Framework\Console\Exception\ConsoleException;

class Console
{
	protected ContainerInterface $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function run(): int
	{
		$argv = $_SERVER['argv'];

		$commandName = $argv[1] ?? 'help';

		if ($commandName === 'help') {
			throw new ConsoleException('Help command not implemented yet');
		}

		$args = array_slice($argv, 2);
		$options = $this->parseOptions($args);

		$command = $this->container->get($commandName);

		$status = $command->execute($options);

		return $status;
	}

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
