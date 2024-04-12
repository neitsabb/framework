<?php

namespace Modules\Generator\Services;

class Formatter
{
	public function format(array $options): string
	{
		$lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';
		$uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$digitsChars = '0123456789';
		$specialcharsChars = '!@#$%^&*()_+{}|:<>?';

		$chars = '';
		$chars .= $options['lowercase'] ? $lowercaseChars : '';
		$chars .= $options['uppercase'] ? $uppercaseChars : '';
		$chars .= $options['digits'] ? $digitsChars : '';
		$chars .= $options['specialchars'] ? $specialcharsChars : '';

		$password = '';
		for ($i = 0; $i < $options['length']; $i++) {
			$password .= $chars[rand(0, strlen($chars) - 1)];
		}

		return $password;
	}
}
