<?php

namespace Neitsab\Framework\Http\Response;

use Neitsab\Framework\Http\Response\Response;


class RedirectResponse extends Response
{
	/**
	 * Create a redirect response 
	 * 
	 * @param string $url - the url to redirect to
	 * @param int $status - the status code
	 * 
	 * @return void
	 */
	public function __construct(string $url, int $status = 302)
	{
		parent::__construct('', $status, ['Location' => $url]);
	}

	/**
	 * Send the response
	 * 
	 * @return void
	 */
	public function send(): void
	{
		header('Location: ' . $this->headers['Location'], true, $this->status);
		exit;
	}
}
