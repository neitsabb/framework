<?php

namespace Neitsab\Framework\Console\Command;

use Neitsab\Framework\Database\Connection;
use Neitsab\Framework\Console\Command\CommandInterface;

class MigrateDatabaseCommand implements CommandInterface
{
	private string $name = 'db:migrate';

	private Connection $connection;

	private string $migrationsPath;

	public function __construct(
		Connection $connection,
		string $migrationsPath
	) {
		$this->connection = $connection;
		$this->migrationsPath = $migrationsPath;
	}

	public function execute(array $params = []): int
	{
		try {

			$this->createMigrationsTable();

			$this->connection->beginTransaction();

			$appliedMigrations = $this->getAppliedMigrations();

			$migrationFiles = $this->getMigrationFiles();

			$migrationsToApply = array_diff($migrationFiles, $appliedMigrations);

			foreach ($migrationsToApply as $migrationFile) {
				require_once $this->migrationsPath . '/' . $migrationFile;
				$className = pathinfo($migrationFile, PATHINFO_FILENAME);
				$migrationInstance = new $className(
					$this->connection,
					$className
				);
				/** @disregard P1009 Undefined type **/
				$migrationInstance->up();

				$this->insertMigration($migrationFile);
			}

			$this->connection->commit();

			return 0;
		} catch (\Throwable $throwable) {
			if ($this->connection->isTransactionActive()) {
				$this->connection->rollBack();
			}
			throw $throwable;
		}
	}

	private function insertMigration(string $migration): void
	{
		$sql = "INSERT INTO migrations (migration) VALUES (?)";
		$statement = $this->connection->prepare($sql);
		$statement->bindValue(1, $migration);
		$statement->execute();
	}

	private function getAppliedMigrations(): array
	{
		$sql = "SELECT migration FROM migrations";
		$statement = $this->connection->prepare($sql);
		$statement->execute();

		$migrations = $statement->fetchAll(\PDO::FETCH_ASSOC);

		return array_column($migrations, 'migration');
	}

	private function createMigrationsTable(): void
	{
		$this->connection->executeQuery(
			"CREATE TABLE IF NOT EXISTS migrations (
				id INT AUTO_INCREMENT PRIMARY KEY,
				migration VARCHAR(255) NOT NULL
			)"
		);
	}

	private function getMigrationFiles()
	{
		$migrationFiles = scandir($this->migrationsPath);

		$files = array_filter(
			$migrationFiles,
			fn ($file) => !in_array($file, ['.', '..'])
		);

		return $files;
	}
}
