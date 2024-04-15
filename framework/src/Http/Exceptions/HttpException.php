<?php

namespace Neitsab\Framework\Http\Exceptions;

class HttpException extends \Exception
{
	private int $statusCode = 400;

	public function getStatusCode(): int
	{
		return $this->statusCode;
	}

	public function setStatusCode(int $statusCode): void
	{
		$this->statusCode = $statusCode;
	}
}
