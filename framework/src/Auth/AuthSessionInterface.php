<?php

namespace Neitsab\Framework\Auth\Contracts;

interface AuthSessionInterface
{
	public function authenticate(string $username, string $password): bool;

	public function login($user);

	public function logout();

	public function getUser();
}
