<?php

namespace Modules\Generator\Services;

use Modules\Formatter\Contracts\FormatterInterface;

class PhoneFormatter implements FormatterInterface
{
	public function format(array|string $value): string
	{
		// $value = country 
		$indicator = '';
		$phone = '';

		if ($value == 'fr') {
			$indicator = '+33';
		} else if ($value == 'be') {
			$indicator = '+32';
		}

		$phone = $indicator . ' ' . rand(600000000, 699999999);

		return $phone;
	}
}
