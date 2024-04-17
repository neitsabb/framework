<?php

namespace Neitsab\Framework\Auth;

use Modules\Auth\Models\User;
use Neitsab\Framework\Auth\Contracts\AuthSessionInterface;
use Neitsab\Framework\Database\Model;
use Neitsab\Framework\Session\Session;
use Neitsab\Framework\Session\SessionInterface;

class SessionAuthentification implements AuthSessionInterface
{
	/**
	 * @var SessionInterface $session
	 */
	private SessionInterface $session;
	public function __construct(SessionInterface $session)
	{
		$this->session = $session;
	}

	/**
	 * Authenticate the user with the given email and password.
	 * 
	 * @param string $email The user email.
	 * @param string $password The user password.
	 * @return bool
	 */
	public function authenticate(string $email, string $password): bool
	{
		$user = User::where('email', $email)->first();

		if (!$user || !password_verify($password, $user?->password)) {
			return false;
		}

		$this->login($user);

		return true;
	}

	public function login(Model $user)
	{
		$this->session->start();
		$this->session->set(Session::AUTH_KEY, $user->id);
	}

	public function logout()
	{
		$this->session->remove(Session::AUTH_KEY);
	}

	public function getUser()
	{
		return $_SESSION['user'] ?? null;
	}
}
