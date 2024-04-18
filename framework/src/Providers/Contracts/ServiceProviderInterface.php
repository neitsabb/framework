<?php

namespace Neitsab\Framework\Providers\Contracts;

interface ServiceProviderInterface
{
	/**
	 * Register the service provider
	 * 
	 * @return void
	 */
	public function register(): void;

	// /**
	//  * Boot the service provider
	//  * 
	//  * @return void
	//  */
	// public function boot(): void;
}
