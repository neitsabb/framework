<?php 

namespace App\Core;

class Exception 
{
	/**
	 * 
	 * The function throws an exception with a message.
	 * 
	 * @param string message The "message" parameter is a string that represents the message of the exception.
	 * 
	 */
	public static function throw(string $message, int $code): void
	{
		throw new \Exception($message, $code);
	}
}