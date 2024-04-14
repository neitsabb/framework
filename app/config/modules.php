<?php

use Modules\Generator\Services\PasswordFormatter;
use Modules\Formatter\Contracts\FormatterInterface;
use Modules\Generator\Controllers\PasswordGenerator;
use Modules\Generator\Controllers\PhoneGenerator;
use Modules\Generator\Services\PhoneFormatter;

return [

	/**
	 * 
	 * Modules folder path
	 * 
	 * This value is the path to the modules folder. This value is used when the
	 * framework needs to load the modules of the application.
	 * 
	 */
	'path' => '/app/modules',


	/**
	 * Array of providers to be binding.
	 * 
	 * @example 
	 * 'providers' => [
	 * 		Interface::class => [ 
	 * 			Concrete::class => Implementation::class 
	 * 		]
	 * ]
	 * 'providers' => [
	 * 		FormatterInterface::class => [
	 * 			PasswordGenerator::class => PasswordFormatter::class,
	 * 			PhoneGenerator::class => PhoneFormatter::class
	 * 		]
	 * ]
	 */
	'providers' => [
		FormatterInterface::class => [
			PasswordGenerator::class => PasswordFormatter::class,
			PhoneGenerator::class => PhoneFormatter::class
		],
	]
];
