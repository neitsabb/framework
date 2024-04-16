<?php

namespace Neitsab\Framework\Auth;

use Neitsab\Framework\Auth\Contracts\AuthSessionInterface;

class SessionAuthentification implements AuthSessionInterface
{
	public function authenticate(string $username, string $password): bool
	{
		return $username === 'admin' && $password === 'admin';
	}

	public function login($user)
	{
		$_SESSION['user'] = $user;
	}

	public function logout()
	{
		unset($_SESSION['user']);
	}

	public function getUser()
	{
		return $_SESSION['user'] ?? null;
	}
}
