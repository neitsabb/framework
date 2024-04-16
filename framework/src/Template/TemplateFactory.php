<?php

namespace Neitsab\Framework\Template;

use Neitsab\Framework\Session\SessionInterface;

class TemplateFactory
{
	protected string $themePath;

	protected SessionInterface $session;

	public function __construct(string $themePath, SessionInterface $session)
	{
		$this->themePath = $themePath;
		$this->session = $session;
	}

	public function make(): Template
	{
		return new Template($this->themePath, $this->session);
	}
}
