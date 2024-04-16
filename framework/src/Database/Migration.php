<?php

namespace Neitsab\Framework\Database;

use Neitsab\Framework\Database\Connection;

class Migration
{
	/**
	 * @var Connection $connection - the database connection
	 */
	protected Connection $connection;

	/**
	 * @var string $table - the table name
	 */
	public string $table;

	public function __construct(Connection $connection, string $table)
	{
		$this->connection = $connection;
		$this->table = $table;
	}

	/**
	 * Run the migration up and create the table
	 */
	protected function up(): void
	{
		$this->createTable();
	}

	/**
	 * Run the migration down and drop the table
	 */
	protected function down(): void
	{
		$this->dropTable();
	}

	/**
	 * Add a string column to the table
	 * 
	 * @param string $column - the column name
	 * @param int $length - the column length
	 * @param bool $nullable - whether the column is nullable
	 * @param string $default - the default value
	 * @return void
	 */
	public function varchar(
		string $column,
		int $length = 255,
		bool $nullable = false,
		string $default = '0.0'
	): void {
		if ($nullable) {
			$this->addColumn($column, "VARCHAR($length) DEFAULT $default");
			return;
		}
		$this->addColumn($column, "VARCHAR($length) NOT NULL");
	}

	/**
	 * Add a text column to the table
	 * 
	 * @param string $column - the column name
	 * @param bool $nullable - whether the column is nullable
	 * @param string $default - the default value
	 * @return void
	 */
	public function mediumText(
		string $column,
		bool $nullable = false,
		string $default = '0.0'
	): void {
		if ($nullable) {
			$this->addColumn($column, "MEDIUMTEXT DEFAULT $default");
			return;
		}
		$this->addColumn($column, "MEDIUMTEXT NOT NULL");
	}

	/**
	 * Add a float column to the table
	 * 
	 * @param string $column - the column name
	 * @param bool $nullable - whether the column is nullable
	 * @param string $default - the default value
	 * @return void
	 */
	public function float(
		string $column,
		bool $nullable = false,
		string $default = '0.0'
	): void {
		if ($nullable) {
			$this->addColumn($column, "FLOAT DEFAULT $default");
			return;
		}
		$this->addColumn($column, "FLOAT NOT NULL");
	}

	/**
	 * Add an integer column to the table
	 * 
	 * @param string $column - the column name
	 * @param bool $nullable - whether the column is nullable
	 * @param string $default - the default value
	 * @return void
	 */
	public function integer(
		string $column,
		bool $nullable = false,
		string $default = '0'
	): void {
		if ($nullable) {
			$this->addColumn($column, "INT DEFAULT $default");
			return;
		}
		$this->addColumn($column, "INT NOT NULL");
	}

	/**
	 * Add a boolean column to the table
	 * 
	 * @param string $column - the column name
	 * @param bool $nullable - whether the column is nullable
	 * @param string $default - the default value
	 * @return void
	 */
	public function bool(
		string $column,
		bool $nullable = false,
		string $default = '0'
	): void {
		if ($nullable) {
			$this->addColumn($column, "BOOLEAN DEFAULT $default");
			return;
		}
		$this->addColumn($column, "BOOLEAN NOT NULL DEFAULT $default");
	}

	/**
	 * Add a foreign key column to the table
	 * 
	 * @param string $column - the column name
	 * @param string $referenceTable - the reference table
	 * @param string $referenceColumn - the reference column
	 * @param string $onDelete - the on delete action
	 * @return void
	 */
	public function foreignKey(
		string $column,
		string $referenceTable,
		string $referenceColumn,
		string $onDelete = 'CASCADE'
	): void {
		$this->addColumn($column, "INT NOT NULL");
		$this->addForeignKey($column, $referenceTable, $referenceColumn, $onDelete);
	}

	/**
	 * Add a primary key column to the table
	 * 
	 * @param string $column - the column name
	 * @return void
	 */
	public function primaryKey(string $column): void
	{
		$this->addColumn($column, "INT NOT NULL AUTO_INCREMENT");
		$this->addPrimaryKey($column);
	}

	/**
	 * Add timestamps to the table
	 * 
	 * @return void
	 */
	public function timestamps(): void
	{
		$this->addColumn('created_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
		$this->addColumn('updated_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
	}

	/**
	 * Update a column in the table
	 * 
	 * @param string $column - the column name
	 * @param string $type - the column type
	 * @return void
	 */
	public function updateColumn(string $column, string $type): void
	{
		$sql = "ALTER TABLE $this->table MODIFY COLUMN $column $type";

		$this->connection->executeQuery($sql);
	}

	/**
	 * Create the table and id column
	 * 
	 * @return void
	 */
	private function createTable(): void
	{
		$this->dropTable();
		$sql = "CREATE TABLE IF NOT EXISTS $this->table (
			id INT AUTO_INCREMENT PRIMARY KEY			
		)";
		$this->connection->executeQuery($sql);
	}

	/**
	 * Drop the table
	 * 
	 * @return void
	 */
	private function dropTable(): void
	{
		$sql = "DROP TABLE IF EXISTS $this->table";
		$this->connection->executeQuery($sql);
	}

	/**
	 * Add a column to the table
	 * 
	 * @param string $column - the column name
	 * @param string $type - the column type
	 * @return void
	 * 
	 * @example $this->addColumn('name', 'VARCHAR(255)');
	 */
	private function addColumn(string $column, string $type): void
	{
		$sql = "ALTER TABLE $this->table ADD COLUMN $column $type";

		$this->connection->manager->exec($sql);
	}

	/**
	 * Drop a column from the table
	 * 
	 * @param string $column - the column name
	 * @return void
	 */
	private function dropColumn(string $column): void
	{
		$sql = "ALTER TABLE $this->table DROP COLUMN $column";

		$this->connection->executeQuery($sql);
	}

	/**
	 * Add a foreign key to the table
	 * 
	 * @param string $column - the column name
	 * @param string $referenceTable - the reference table
	 * @param string $referenceColumn - the reference column
	 * @param string $onDelete - the on delete action
	 * @return void
	 */
	private function addForeignKey(
		string $column,
		string $referenceTable,
		string $referenceColumn,
		string $onDelete = 'CASCADE'
	): void {
		$sql = "ALTER TABLE $this->table ADD FOREIGN KEY ($column) REFERENCES $referenceTable($referenceColumn ) ON DELETE $onDelete";

		$this->connection->executeQuery($sql);
	}

	/**
	 * Drop a foreign key from the table
	 * 
	 * @param string $constraintName - the constraint name
	 * @return void
	 */
	private function dropForeignKey(string $constraintName): void
	{
		$sql = "ALTER TABLE $this->table DROP FOREIGN KEY $constraintName";
		$this->connection->executeQuery($sql);
	}

	/**
	 * Add a primary key to the table
	 * 
	 * @param string $column - the column name
	 * @return void
	 */
	private function addPrimaryKey(string $column): void
	{
		$sql = "ALTER TABLE $this->table ADD COLUMN $column PRIMARY KEY ($column)";

		$this->connection->executeQuery($sql);
	}

	/**
	 * Drop a primary key from the table
	 * 
	 * @param string $column - the column name
	 * @return void
	 */
	private function dropPrimaryKey(string $column): void
	{
		$sql = "ALTER TABLE $this->table DROP PRIMARY KEY ($column)";

		$this->connection->executeQuery($sql);
	}
}
