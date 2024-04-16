<?php

namespace Neitsab\Framework\Database;


use Neitsab\Framework\Core\Config;

class ConnectionFactory
{
	/**
	 * @var Config $config - the configuration
	 */
	private Config $config;

	/**
	 * @var string $defaultConnection - the default connection
	 */
	public string $defaultConnection;

	public function __construct(Config $config)
	{
		$this->config = $config;
		$this->defaultConnection = $this->config->get('database.default');
	}

	/**
	 * Make a connection to the database based on the default connection config
	 * 
	 * @return Connection
	 */
	public function make(): Connection
	{
		switch ($this->defaultConnection) {
			case 'mysql':
				$connection = $this->makeMysqlConnection();
				break;
		}

		return new Connection($connection);
	}

	/**
	 * Make a connection to a MySQL database
	 * 
	 * @return \PDO
	 */
	private function makeMysqlConnection(): \PDO
	{
		$host = $this->config->get('database.connections.mysql.host');
		$port = $this->config->get('database.connections.mysql.port');
		$dbname = $this->config->get('database.connections.mysql.database');
		$user = $this->config->get('database.connections.mysql.username');
		$pass = $this->config->get('database.connections.mysql.password');

		$dsn = "mysql:host=$host;port=$port;dbname=$dbname";

		$pdo = new \PDO($dsn, $user, $pass);
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$pdo->exec("USE " . $dbname);

		return $pdo;
	}
}
