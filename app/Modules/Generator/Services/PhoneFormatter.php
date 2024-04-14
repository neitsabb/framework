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

		switch ($value) {
			case 'fr':
				$indicator = '+33';
				break;
			case 'be':
				$indicator = '+32';
				break;
			case 'us':
				$indicator = '+1';
				break;
			case 'uk':
				$indicator = '+44';
				break;
			case 'de':
				$indicator = '+49';
				break;
			case 'es':
				$indicator = '+34';
				break;
			default:
				$indicator = '+33';
				break;
		}

		$phone = $indicator . ' ' . implode(' ', str_split(rand(400000000, 699999999), 2));

		return $phone;
	}
}
