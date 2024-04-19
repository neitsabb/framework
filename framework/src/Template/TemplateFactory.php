<?php

namespace Neitsab\Framework\Template;

use Neitsab\Framework\Http\Request;
use Neitsab\Framework\Database\Connection;
use Neitsab\Framework\Session\SessionInterface;

class TemplateFactory
{
	/**
	 * @var string $themePath - The path to the theme.
	 */
	protected string $themePath;

	/**
	 * @var SessionInterface $session - The session.
	 */
	protected SessionInterface $session;

	/**
	 * @var Connection $connection - The database connection.
	 */
	protected Connection $connection;



	public function __construct(string $themePath, SessionInterface $session, Connection $connection)
	{
		$this->themePath = $themePath;
		$this->session = $session;
		$this->connection = $connection;
	}

	/**
	 * Make a new template instance.
	 * 
	 * @return Template
	 */
	public function make(): Template
	{
		return new Template(
			$this->themePath,
			$this->session,
			$this->connection,
		);
	}
}
