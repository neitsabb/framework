<?php

namespace Modules\Generator\Services;

use Modules\Formatter\Contracts\FormatterInterface;

class PasswordFormatter implements FormatterInterface
{
	public function format(array|string $value): string
	{
		$lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';
		$uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$digitsChars = '0123456789';
		$specialcharsChars = '!@#$%^&*()_+{}|:<>?';

		$chars = '';
		$chars .= $value['lowercase'] ? $lowercaseChars : '';
		$chars .= $value['uppercase'] ? $uppercaseChars : '';
		$chars .= $value['digits'] ? $digitsChars : '';
		$chars .= $value['specialchars'] ? $specialcharsChars : '';

		$password = '';
		for ($i = 0; $i < $value['length']; $i++) {
			$password .= $chars[rand(0, strlen($chars) - 1)];
		}

		return $password;
	}
}
