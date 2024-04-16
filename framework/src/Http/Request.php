<?php

namespace Neitsab\Framework\Http;

class Request
{
    public readonly array $getParams;
    public readonly array $postParams;
    public readonly array $cookies;
    public readonly array $files;
    public readonly array $server;


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

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD']);
    }

    public function uri(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->postParams[$key] ?? $default;
    }

    public function param(string $key, mixed $default = null): mixed
    {
        return $this->getParams[$key] ?? $default;
    }
}
