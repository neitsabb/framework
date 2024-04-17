<?php

namespace Neitsab\Framework\Session;

use Neitsab\Framework\Core\Application;
use Neitsab\Framework\Session\SessionInterface;

class Session implements SessionInterface
{
	/**
	 * The key to store the user id in the session.
	 */
	public const AUTH_KEY = 'auth_id';

	/**
	 * @var string $flashKey - the flash key
	 */
	private string $flashKey;

	public function __construct()
	{
		$this->flashKey = Application::$config->get('session.flash_key');
	}

	/**
	 * Start the session
	 * 
	 * @return void
	 */
	public function start(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}

	/**
	 * Destroy the session
	 * 
	 * @return void
	 */
	public function destroy(): void
	{
		session_destroy();
	}

	/**
	 * Regenerate the session id
	 * 
	 * @return void
	 */
	public function regenerate(): void
	{
		session_regenerate_id();
	}

	/**
	 * Set a session value
	 * 
	 * @param string $key - the key
	 * @param mixed $value - the value
	 * @return void
	 */
	public function set(string $key, $value): void
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * Get a session value
	 * 
	 * @param string $key - the key
	 * @param mixed $default - the default value
	 * @return mixed
	 */
	public function get(string $key, $default = null): mixed
	{
		return $_SESSION[$key] ?? $default;
	}

	/**
	 * Remove a session value
	 * 
	 * @param string $key - the key
	 * @return void
	 */
	public function remove(string $key): void
	{
		unset($_SESSION[$key]);
	}

	/**
	 * Check if a session key exists
	 * 
	 * @param string $key - the key
	 * @return bool
	 */
	public function has(string $key): bool
	{
		return isset($_SESSION[$key]);
	}

	/**
	 * Get a flash message
	 * 
	 * @param string $type - the flash type
	 * @return array
	 */
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

	/**
	 * Set a flash message
	 * 
	 * @param string $type - the flash type
	 * @param string $message - the flash message
	 * @return void
	 */
	public function setFlash(string $type, string $message): void
	{
		$flash = $this->get($this->flashKey) ?? [];
		$flash[$type][] = $message;
		$this->set($this->flashKey, $flash);
	}

	/**
	 * Check if a flash message exists
	 * 
	 * @param string $type - the flash type
	 * @return bool
	 */
	public function hasFlash(string $type): bool
	{
		return isset($_SESSION[$this->flashKey][$type]);
	}

	/**
	 * Clear all flash messages
	 * 
	 * @return void
	 */
	public function clearFlash(): void
	{
		unset($_SESSION[$this->flashKey]);
	}

	/**
	 * Set the previous url
	 * 
	 * @param string $url - the previous url
	 * @return void
	 */
	public function setPreviousUrl(string $url): void
	{
		$this->set('previous_url', $url);
	}

	/**
	 * Get the previous url
	 * 
	 * @return string
	 */
	public function getPreviousUrl(): string
	{
		return $this->get('previous_url', '');
	}

	/**
	 * Check if the user is authenticated
	 * 
	 * @return bool
	 */
	public function isAuth(): bool
	{
		return $this->has(self::AUTH_KEY);
	}
}
