<?php

namespace Neitsab\Framework\Http;

use Neitsab\Framework\Session\SessionInterface;

class Request
{
    /**
     * @var array $getParams - The GET parameters.
     */
    public readonly array $getParams;

    /**
     * @var array $postParams - The POST parameters.
     */
    public readonly array $postParams;

    /**
     * @var array $cookies - The cookies of the request.
     */
    public readonly array $cookies;

    /**
     * @var array $files - The files of the request.
     */
    public readonly array $files;

    /**
     * @var array $server - The server parameters.
     */
    public readonly array $server;

    /**
     * @var SessionInterface $session - The session instance.
     */
    public SessionInterface $session;

    public function __construct(
        array $getParams,
        array $postParams,
        array $cookies,
        array $files,
        array $server,
    ) {
        $this->getParams = $getParams;
        $this->postParams = $postParams;
        $this->cookies = $cookies;
        $this->files = $files;
        $this->server = $server;
    }

    /**
     * Capture the request from the global variables and return a new instance.
     */
    public static function capture(): static
    {
        return new static(
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES,
            $_SERVER
        );
    }

    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Get the request method.
     * 
     * @return string
     */
    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD']);
    }

    /**
     * Get the request URI.
     * 
     * @return string
     */
    public function uri(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function inputs(): array
    {
        return $this->postParams;
    }

    public function params(): array
    {
        return $this->getParams;
    }
    /**
     * Get the request input.
     * 
     * @param string $key - The key to get from the POST parameters.
     * @param mixed $default - The default value if the key does not exist.
     * @return mixed
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->postParams[$key] ?? $default;
    }

    /**
     * Get the request parameter.
     * 
     * @param string $key - The key to get from the GET parameters.
     * @param mixed $default - The default value if the key does not exist.
     * @return mixed
     */
    public function param(string $key, mixed $default = null): mixed
    {
        return $this->getParams[$key] ?? $default;
    }
}
