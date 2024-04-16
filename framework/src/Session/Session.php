<?php

namespace Neitsab\Framework\Session;

use Neitsab\Framework\Session\SessionInterface;

class Session implements SessionInterface
{
	private string $flashKey;

	public function __construct(string $flashKey)
	{
		$this->flashKey = $flashKey;
		session_start();
	}

	public function set(string $key, $value): void
	{
		$_SESSION[$key] = $value;
	}

	public function get(string $key, $default = null)
	{
		return $_SESSION[$key] ?? $default;
	}

	public function remove(string $key): void
	{
		unset($_SESSION[$key]);
	}

	public function has(string $key): bool
	{
		return isset($_SESSION[$key]);
	}

	public function getFlash(string $type): array
	{
		$flash = $this->get($this->flashKey) ?? [];
		if (isset($flash[$type])) {
			$messages = $flash[$type];
			unset($flash[$type]);
			$this->set($this->flashKey, $flash);
			return $messages;
		}

		return [];
	}

	public function setFlash(string $type, string $message): void
	{
		$flash = $this->get($this->flashKey) ?? [];
		$flash[$type][] = $message;
		$this->set($this->flashKey, $flash);
	}

	public function hasFlash(string $type): bool
	{
		return isset($_SESSION[$this->flashKey][$type]);
	}

	public function clearFlash(): void
	{
		unset($_SESSION[$this->flashKey]);
	}
}
