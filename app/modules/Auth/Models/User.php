<?php

namespace Modules\Auth\Models;

use Neitsab\Framework\Auth\AuthUserInterface;
use Neitsab\Framework\Database\Model;

class User extends Model
{
	public static function table(): string
	{
		return 'users';
	}

	public static function getAttributes(): array
	{
		return ['id', 'username', 'email', 'password'];
	}
}
