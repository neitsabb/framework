<?php

namespace Neitsab\Framework\Providers;

use Modules\Listeners\ContentLengthListener;
use Neitsab\Framework\Events\EventDispatcher;
use Neitsab\Framework\Http\Events\ResponseEvent;
use Neitsab\Framework\Providers\Contracts\ServiceProviderInterface;

class EventServiceProvider implements ServiceProviderInterface
{
	private array $listeners = [
		ResponseEvent::class => [
			ContentLengthListener::class
		]
	];

	private EventDispatcher $eventDispatcher;

	public function __construct(EventDispatcher $eventDispatcher)
	{
		$this->eventDispatcher = $eventDispatcher;
	}
	public function register(): void
	{
		// Register the listeners
		foreach ($this->listeners as $eventName => $listeners) {
			foreach (array_unique($listeners) as $listener) {
				$this->eventDispatcher->addListener($eventName, new $listener());
			}
		}
	}
}
