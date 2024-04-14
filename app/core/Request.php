<?php

namespace App\Core;


class Request
{

    public function __construct(
        public readonly array $getParams,
        public readonly array $postParams,
        public readonly array $cookies,
        public readonly array $files,
        public readonly array $server,
    ) {
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
    // /**
    //  * @var array $body - The body of the request.
    //  */
    // public array $body = [];

    // /**
    //  * @var array $params - The parameters of the request.
    //  */
    // public array $params = [];

    // /**
    //  * Checks if the request is a GET or POST request and sanitizes the input.
    //  */
    // public function __construct()
    // {
    //     if ($this->isGet()) {
    //         foreach ($_GET as $key => $value) {
    //             $this->body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
    //         }
    //     }
    //     if ($this->isPost()) {
    //         foreach ($_POST as $key => $value) {
    //             $this->body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
    //         }
    //         foreach ($_FILES as $key => $file) {
    //             $this->body[$key] = $file;
    //         }
    //     }
    // }

    // /**
    //  * Retrieves all the values from the body array.
    //  * 
    //  * @return array The body array.
    //  */
    // public function all(): array
    // {
    //     return $this->body;
    // }

    // /**
    //  * Retrieves the value of a key from the body array.
    //  * 
    //  * @param string key The key to retrieve the value from.
    //  * @return mixed The value of the key from the body array.
    //  */
    // public function getBody(string $key)
    // {
    //     return $this->body[$key] ?? null;
    // }

    // /**
    //  * Retrieves the current URL path from the `['REQUEST_URI']`
    //  * variable and removes any query parameters.
    //  * 
    //  * @return string the path of the current request URI.
    //  */
    // public function getPath(): string
    // {
    //     $path = $_SERVER['REQUEST_URI'];
    //     $position = strpos($path, '?');

    //     return $position ? substr($path, 0, $position) : $path;
    // }

    // /**
    //  * Check if the current URL path starts with a given string.
    //  *  
    //  * @param string path The path to check if the current URL path starts with.
    //  * @return bool if the current URL path starts with the given string.
    //  */
    // public function startsWith(string $path): bool
    // {
    //     return strpos($this->getPath(), $path) === 0;
    // }

    // /**
    //  * Returns the HTTP request method in lowercase.
    //  * 
    //  * @return string The method being returned is the lowercase value of the 'REQUEST_METHOD' server
    //  * variable.
    //  */
    // public function getMethod(): string
    // {
    //     return strtoupper($_SERVER['REQUEST_METHOD']);
    // }

    // /**
    //  * Check if the HTTP method used is GET.
    //  * 
    //  * @return bool indicating whether the HTTP request method is 'GET' or not.
    //  */
    // public function isGet(): bool
    // {
    //     return $this->getMethod() === 'GET';
    // }

    // /**
    //  * Check if the HTTP method used is POST.
    //  * 
    //  * @return bool indicating whether the HTTP request method is 'POST' or not.
    //  */
    // public function isPost(): bool
    // {
    //     return $this->getMethod() === 'POST';
    // }

    // /**
    //  * Set the parameters of an object and returns the object itself.
    //  * 
    //  * @param array params An array of parameters that will be set for the object.
    //  * @return self The method is returning an instance of the class itself (self).
    //  */
    // public function setParams(array $params): self
    // {
    //     $this->params = $params;
    //     return $this;
    // }

    // /**
    //  * Returns an array of parameters or null if no parameters are set.
    //  * 
    //  * @return array an array of parameters. If no parameters are set, the function returns null.
    //  */
    // public function getParams(): array
    // {
    //     return $this->params ?? null;
    // }
}
