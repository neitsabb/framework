<?php

namespace Neitsab\Framework\Database;

abstract class Model
{
	protected static ?Connection $connection = null;

	abstract public static function table(): string;

	abstract public static function getAttributes(): array;

	public static function all(string $filter = 'id', string $order = 'ASC'): array
	{
		if (is_null(static::$connection)) {
			throw new \Exception('Database connection is not set.');
		}

		$table = static::table();
		$attributes = static::getAttributes();
		$attributes = implode(', ', $attributes);

		$sql = "SELECT $attributes FROM $table ORDER BY $filter $order";

		$stmt = static::$connection->manager->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	public static function setConnection(Connection $connection)
	{
		static::$connection = $connection;
	}
}
