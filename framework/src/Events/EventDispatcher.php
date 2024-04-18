<?php

namespace Neitsab\Framework\Events;

use Neitsab\Framework\Http\Events\ResponseEvent;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
{
	protected $listeners = [];

	public function dispatch(object $event)
	{
		foreach ($this->getListenersForEvent($event) as $listener) {
			// Break if propagation stopped
			if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
				return $event;
			}
			// Call the listener, passing in the event (each listener will be a callable)
			$listener($event);
		}
	}

	public function getListenersForEvent(object $event): iterable
	{
		$eventName = get_class($event);

		if (array_key_exists($eventName, $this->listeners)) {
			return $this->listeners[$eventName];
		}

		return [];
	}

	public function addListener(string $eventName, callable $listener)
	{
		$this->listeners[$eventName][] = $listener;

		return $this;
	}
}
