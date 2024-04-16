<?php

namespace Neitsab\Framework\Database;

class Connection
{
	public mixed $manager;

	public function __construct(mixed $manager)
	{
		$this->manager = $manager;
	}

	public function getManager(): mixed
	{
		return $this->manager;
	}

	public function beginTransaction(): void
	{
		$this->manager->beginTransaction();
	}

	public function commit(): void
	{
		$this->manager->commit();
	}

	public function isTransactionActive(): bool
	{
		return $this->manager->inTransaction();
	}
	public function rollBack(): void
	{
		$this->manager->rollBack();
	}

	public function executeQuery(string $sql): void
	{
		$this->manager->query($sql);
	}

	public function prepare(string $sql): mixed
	{
		return $this->manager->prepare($sql);
	}

	public function getDatabasePlatform(): mixed
	{
		return $this->manager->getDatabasePlatform();
	}
}
