<?php

namespace Neitsab\Framework\Http\Events;

use Neitsab\Framework\Events\Event;
use Neitsab\Framework\Http\Request\Request;
use Neitsab\Framework\Http\Response\Response;

class ResponseEvent extends Event
{
	public Request $request;

	public Response $response;

	public function __construct(Request $request, Response $response)
	{
		$this->request = $request;
		$this->response = $response;
	}

	public function getRequest(): Request
	{
		return $this->request;
	}

	public function getResponse(): Response
	{
		return $this->response;
	}
}
