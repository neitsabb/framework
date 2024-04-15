<?php

namespace Neitsab\Framework\Database;

use Doctrine\DBAL\Connection;

use Neitsab\Framework\Core\Config;

class ConnectionFactory
{
	private Config $config;

	public string $defaultConnection;

	public function __construct(Config $config)
	{
		$this->config = $config;
		$this->defaultConnection = $this->config->get('database.default');
	}

	public function make(): Connection
	{
		$defaultConnection = $this->config->get('database.default');
		$connectionParams = $this->config->get("database.connections.{$defaultConnection}");

		return \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
	}
}