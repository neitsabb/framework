<?php

use Neitsab\Framework\Database\Connection;
use Neitsab\Framework\Database\Migration;


class m002_create_users_table extends Migration
{
	public string $table = 'users';

	public function __construct(Connection $connection)
	{
		parent::__construct(
			$connection,
			$this->table,
		);
	}

	public function up(): void
	{
		parent::up();

		$this->varchar('username');
		$this->varchar('email');
		$this->varchar('password');
		$this->timestamps();
	}

	public function down(): void
	{
		parent::down();
		// Table drop / modification code goes here

		echo get_class($this) . ' "down" method called' . PHP_EOL;
	}
};
