<?php

namespace Neitsab\Framework\Http\Exceptions;

class HttpException extends \Exception
{
	/**
	 * @var int $statusCode - the status code
	 */
	private int $statusCode = 400;

	/**
	 * Get the status code
	 * 
	 * @return int - the status code
	 */
	public function getStatusCode(): int
	{
		return $this->statusCode;
	}

	/**
	 * Set the status code
	 * 
	 * @param int $statusCode - the status code
	 * @return void
	 */
	public function setStatusCode(int $statusCode): void
	{
		$this->statusCode = $statusCode;
	}
}
