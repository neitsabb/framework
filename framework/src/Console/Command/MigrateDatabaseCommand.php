<?php

namespace Neitsab\Framework\Console\Command;

use Doctrine\DBAL\Types\Types;
use Doctrine\DBAL\Schema\Schema;
use Neitsab\Framework\Console\Command\CommandInterface;
use Neitsab\Framework\Database\Type\VarcharType;

class MigrateDatabaseCommand implements CommandInterface
{
	private string $name = 'db:migrate';

	private \Doctrine\DBAL\Connection $connection;

	public function __construct(\Doctrine\DBAL\Connection $connection)
	{
		$this->connection = $connection;
	}

	public function execute(array $params = []): int
	{
		try {

			// Create a migrations table SQL if table not already in existence
			$this->createMigrationsTable();

			$this->connection->beginTransaction();


			$appliedMigrations = $this->getAppliedMigrations();


			// Get the $migrationFiles from the migrations folder

			// Get the migrations to apply. i.e. they are in $migrationFiles but not in $appliedMigrations

			// Create SQL for any migrations which have not been run ..i.e. which are not in the database

			// Add migration to database

			// Execute the SQL query

			$this->connection->commit();

			return 0;
		} catch (\Throwable $throwable) {

			// $this->connection->rollBack();

			throw $throwable;
		}
	}

	private function getAppliedMigrations(): array
	{
		$sql = "SELECT migration FROM migrations;";

		$appliedMigrations = $this->connection->executeQuery($sql)->fetchFirstColumn();

		return $appliedMigrations;
	}

	private function createMigrationsTable(): void
	{
		$schemaManager = $this->connection->createSchemaManager();

		if (!$schemaManager->tablesExist(['migrations'])) {
			$schema = new Schema();
			$table = $schema->createTable('migrations');
			$table->addColumn('id', Types::INTEGER, ['unsigned' => true, 'autoincrement' => true]);
			$table->addColumn('migration', Types::STRING, ['length' => 255]);
			$table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
			$table->setPrimaryKey(['id']);

			$sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

			$this->connection->executeQuery($sqlArray[0]);

			echo 'migrations table created' . PHP_EOL;
		}
	}
}
