<?php

namespace Neitsab\Framework\Console\Command;

use Neitsab\Framework\Database\Connection;
use Neitsab\Framework\Console\Command\CommandInterface;

class MigrateDatabaseCommand implements CommandInterface
{
	/**
	 * @var string $name - the command name
	 */
	private string $name = 'db:migrate';

	/**
	 * @var Connection $connection - the database connection
	 */
	private Connection $connection;

	/**
	 * @var string $migrationsPath - the path to the migrations directory
	 */
	private string $migrationsPath;

	public function __construct(Connection $connection, string $migrationsPath)
	{
		$this->connection = $connection;
		$this->migrationsPath = $migrationsPath;
	}

	/**
	 * Execute the command
	 * 
	 * @param array $params - the command line options
	 * @return int - the status
	 */
	public function execute(array $params = []): int
	{
		try {
			// Create the migrations table if it does not exist
			$this->createMigrationsTable();

			// Begin a transaction
			$this->connection->beginTransaction();

			// Get the applied migrations
			$appliedMigrations = $this->getAppliedMigrations();

			// Get the migration files
			$migrationFiles = $this->getMigrationFiles();

			// Get the migrations that need to be applied
			$migrationsToApply = array_diff($migrationFiles, $appliedMigrations);

			// Apply the migrations
			foreach ($migrationsToApply as $migrationFile) {
				require_once $this->migrationsPath . '/' . $migrationFile;
				$className = pathinfo($migrationFile, PATHINFO_FILENAME);
				$migrationInstance = new $className(
					$this->connection,
					$className
				);
				/** @disregard P1009 Undefined type **/
				$migrationInstance->up();

				// Insert the migration into the migrations table
				$this->insertMigration($migrationFile);
			}

			// Commit the transaction
			$this->connection->commit();

			return 0;
		} catch (\Throwable $throwable) {
			if ($this->connection->isTransactionActive()) {
				$this->connection->rollBack();
			}
			throw $throwable;
		}
	}

	/**
	 * Insert a migration into the migrations table
	 * 
	 * @param string $migration - the migration file name
	 * @return void
	 */
	private function insertMigration(string $migration): void
	{
		$sql = "INSERT INTO migrations (migration) VALUES (?)";
		$statement = $this->connection->prepare($sql);
		$statement->bindValue(1, $migration);
		$statement->execute();
	}

	/**
	 * Get the applied migrations
	 * 
	 * @return array - the applied migrations
	 */
	private function getAppliedMigrations(): array
	{
		$sql = "SELECT migration FROM migrations";
		$statement = $this->connection->prepare($sql);
		$statement->execute();

		$migrations = $statement->fetchAll(\PDO::FETCH_ASSOC);

		return array_column($migrations, 'migration');
	}

	/**
	 * Create the migrations table if it does not exist
	 * 
	 * @return void
	 */
	private function createMigrationsTable(): void
	{
		$this->connection->executeQuery(
			"CREATE TABLE IF NOT EXISTS migrations (
				id INT AUTO_INCREMENT PRIMARY KEY,
				migration VARCHAR(255) NOT NULL
			)"
		);
	}

	/**
	 * Get the migration files
	 * 
	 * @return array - the migration files
	 */
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
