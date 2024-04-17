<?php

namespace Neitsab\Framework\Auth\Contracts;

use Neitsab\Framework\Database\Model;

interface AuthSessionInterface
{
	public function authenticate(string $username, string $password): bool;

	public function login(Model $user);

	public function logout();

	public function getUser();
}
