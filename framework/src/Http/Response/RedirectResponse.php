<?php

namespace Neitsab\Framework\Http\Response;

use Neitsab\Framework\Http\Response\Response;


class RedirectResponse extends Response
{
	public function __construct(string $url, int $status = 302)
	{
		parent::__construct('', $status, ['Location' => $url]);
	}

	public function send(): void
	{
		header('Location: ' . $this->headers['Location'], true, $this->status);
		exit;
	}
}
