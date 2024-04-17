<?php

namespace Neitsab\Framework\Database;

abstract class Model
{
	private string $query;

	private array $params;

	protected static ?Connection $connection = null;

	abstract public static function table(): string;

	abstract public static function getAttributes(): array;

	public static function create(array $attributes): self
	{
		$table = static::table();
		$attributes = array_intersect_key($attributes, array_flip(static::getAttributes()));

		$columns = implode(', ', array_keys($attributes));
		$values = ':' . implode(', :', array_keys($attributes));

		$sql = "INSERT INTO $table ($columns) VALUES ($values)";

		$stmt = static::$connection->manager->prepare($sql);
		$stmt->execute($attributes);

		return static::where('id', static::$connection->manager->lastInsertId())->first();
	}

	public static function all(string $filter = 'id', string $order = 'ASC'): array
	{
		$table = static::table();

		$sql = "SELECT * FROM $table ORDER BY $filter $order";

		return static::performQuery($sql);
	}

	public static function where(
		string $column,
		mixed $value,
		string $operator = '=',
		string $filter = 'id',
		string $order = 'ASC'
	): self {
		$instance = new static();
		$instance->query = "SELECT * FROM " . static::table() . " WHERE $column $operator :email ORDER BY $filter $order";
		$instance->params = ['email' => $value];
		return $instance;
	}

	public function first()
	{
		$results = static::performQuery($this->query, $this->params);
		return $results ? $results[0] : null;
	}

	public function get()
	{
		return static::performQuery($this->query, $this->params);
	}

	protected static function performQuery(string $sql, array $params = []): array
	{
		if (is_null(static::$connection)) {
			throw new \Exception('Database connection is not set.');
		}

		$stmt = static::$connection->manager->prepare($sql);
		$stmt->execute($params);

		return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
	}
	public static function setConnection(Connection $connection)
	{
		static::$connection = $connection;
	}
}
