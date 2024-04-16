<?php

namespace Neitsab\Framework\Database;

use Neitsab\Framework\Database\Connection;

class Migration
{
	protected Connection $connection;
	public string $table;

	public function __construct(Connection $connection, string $table)
	{
		$this->connection = $connection;
		$this->table = $table;
	}

	protected function up()
	{
		$this->createTable();
	}

	protected function down()
	{
		$this->dropTable();
	}

	private function addColumn(string $column, string $type)
	{
		$sql = "ALTER TABLE $this->table ADD COLUMN $column $type";

		$this->connection->manager->exec($sql);
	}

	public function varchar(string $column, int $length = 255, bool $nullable = false, string $default = '0.0')
	{
		if ($nullable) {
			$this->addColumn($column, "VARCHAR($length) DEFAULT $default");
			return;
		}
		$this->addColumn($column, "VARCHAR($length) NOT NULL");
	}

	public function mediumText(string $column, bool $nullable = false, string $default = '0.0')
	{
		if ($nullable) {
			$this->addColumn($column, "MEDIUMTEXT DEFAULT $default");
			return;
		}
		$this->addColumn($column, "MEDIUMTEXT NOT NULL");
	}

	public function float(string $column, bool $nullable = false, string $default = '0.0')
	{
		if ($nullable) {
			$this->addColumn($column, "FLOAT DEFAULT $default");
			return;
		}
		$this->addColumn($column, "FLOAT NOT NULL");
	}

	public function integer(string $column, bool $nullable = false, string $default = '0')
	{
		if ($nullable) {
			$this->addColumn($column, "INT DEFAULT $default");
			return;
		}
		$this->addColumn($column, "INT NOT NULL");
	}

	public function bool(string $column, bool $nullable = false, string $default = '0')
	{
		if ($nullable) {
			$this->addColumn($column, "BOOLEAN DEFAULT $default");
			return;
		}
		$this->addColumn($column, "BOOLEAN NOT NULL DEFAULT $default");
	}

	public function foreignKey(string $column, string $referenceTable, string $referenceColumn, string $onDelete = 'CASCADE')
	{
		$this->addColumn($column, "INT NOT NULL");
		$this->addForeignKey($column, $referenceTable, $referenceColumn, $onDelete);
	}

	public function primaryKey(string $column)
	{
		$this->addColumn($column, "INT NOT NULL AUTO_INCREMENT");
		$this->addPrimaryKey($column);
	}

	public function addTimestamps()
	{
		$this->addColumn('created_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
		$this->addColumn('updated_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
	}

	public function dropColumn(string $column)
	{
		$sql = "ALTER TABLE $this->table DROP COLUMN $column";

		$this->connection->executeQuery($sql);
	}

	public function updateColumn(string $column, string $type)
	{
		$sql = "ALTER TABLE $this->table MODIFY COLUMN $column $type";

		$this->connection->executeQuery($sql);
	}


	public function addForeignKey(string $column, string $referenceTable, string $referenceColumn, string $onDelete = 'CASCADE')
	{
		$sql = "ALTER TABLE $this->table ADD FOREIGN KEY ($column) REFERENCES $referenceTable($referenceColumn ) ON DELETE $onDelete";

		$this->connection->executeQuery($sql);
	}

	public function dropForeignKey(string $constraintName)
	{
		$sql = "ALTER TABLE $this->table DROP FOREIGN KEY $constraintName";
		$this->connection->executeQuery($sql);
	}

	public function addPrimaryKey(string $column)
	{
		$sql = "ALTER TABLE $this->table ADD COLUMN $column PRIMARY KEY ($column)";

		$this->connection->executeQuery($sql);
	}

	public function dropPrimaryKey(string $column)
	{
		$sql = "ALTER TABLE $this->table DROP PRIMARY KEY ($column)";

		$this->connection->executeQuery($sql);
	}
	private function createTable()
	{
		$this->dropTable();
		$sql = "CREATE TABLE IF NOT EXISTS $this->table (
			id INT AUTO_INCREMENT PRIMARY KEY			
		)";
		$this->connection->executeQuery($sql);
	}

	private function dropTable()
	{
		$sql = "DROP TABLE IF EXISTS $this->table";
		$this->connection->executeQuery($sql);
	}
}
