<?php

namespace Neitsab\Framework\Database;

class Connection
{
	/**
	 * @var mixed $manager - the database manager
	 */
	public mixed $manager;

	public function __construct(mixed $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * Get the database manager
	 * 
	 * @return mixed - the database manager
	 */
	public function getManager(): mixed
	{
		return $this->manager;
	}

	/**
	 * Begin a transaction
	 * 
	 * @return void
	 */
	public function beginTransaction(): void
	{
		$this->manager->beginTransaction();
	}

	/**
	 * Commit a transaction
	 * 
	 * @return void
	 */
	public function commit(): void
	{
		if ($this->isTransactionActive()) {
			$this->manager->commit();
		}
	}

	/**
	 * Check if a transaction is active
	 * 
	 * @return bool
	 */
	public function isTransactionActive(): bool
	{
		return $this->manager->inTransaction();
	}

	/**
	 * Rollback a transaction
	 * 
	 * @return void
	 */
	public function rollBack(): void
	{
		$this->manager->rollBack();
	}

	/**
	 * Execute a query
	 * 
	 * @param string $sql - the SQL query
	 * @return void
	 */
	public function executeQuery(string $sql): void
	{
		$this->manager->query($sql);
	}

	/**
	 * Prepare a query
	 * 
	 * @param string $sql - the SQL query
	 * @return mixed
	 */
	public function prepare(string $sql): mixed
	{
		return $this->manager->prepare($sql);
	}

	/**
	 * Get the database platform
	 * 
	 * @return mixed
	 */
	public function getDatabasePlatform(): mixed
	{
		return $this->manager->getDatabasePlatform();
	}
}
