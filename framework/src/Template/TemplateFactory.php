<?php

namespace Neitsab\Framework\Template;

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

	public function __construct(string $themePath, SessionInterface $session)
	{
		$this->themePath = $themePath;
		$this->session = $session;
	}

	/**
	 * Make a new template instance.
	 * 
	 * @return Template
	 */
	public function make(): Template
	{
		return new Template($this->themePath, $this->session);
	}
}
