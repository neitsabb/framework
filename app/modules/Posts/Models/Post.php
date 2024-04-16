<?php

namespace Modules\Posts\Models;

use Neitsab\Framework\Database\Model;

class Post extends Model
{
	public static function table(): string
	{
		return 'posts';
	}

	public static function getAttributes(): array
	{
		return ['id', 'title', 'content'];
	}
}
