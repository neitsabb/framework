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
        $this->getParams = $this->sanitize('GET', $getParams);
        $this->postParams = $this->sanitize('POST', $postParams);
        $this->cookies = $cookies;
        $this->files = $files;
        $this->server = $server;
    }

    public function validate(array $rules): array
    {
        $errors = [];

        foreach ($rules as $key => $rule) {
            $input = $this->input($key) ?? null;

            $rulesParts = explode('|', $rule);

            foreach ($rulesParts as $rulePart) {
                $ruleDetails = explode(':', $rulePart);

                $ruleName = $ruleDetails[0];
                $ruleValue = $ruleDetails[1] ?? null;

                $errors[$key] = $this->validateRule($input, $ruleName, $ruleValue, $key);
            }
        }

        return $errors;
    }

    public function validateRule(
        mixed $input,
        string $ruleName,
        mixed $ruleValue,
        string $key
    ) {
        switch ($ruleName) {
            case 'required':
                if ($input === null || $input === '' || empty($input)) {
                    return 'The ' . $key . ' field is required.';
                }
                break;
            case 'email':
                if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
                    return 'The ' . $key . ' field must be a valid email address.';
                }
                break;
            case 'min':
                if (strlen($input) < $ruleValue) {
                    return 'The ' . $key . ' field must be at least ' . $ruleValue . ' characters.';
                }
                break;
            case 'max':
                if (strlen($input) > $ruleValue) {
                    return 'The ' . $key . ' field must be at most ' . $ruleValue . ' characters.';
                }
                break;
            case 'unique':
                // Check if the value is unique in the database
                break;
            default:
                throw new \Exception('The validation rule ' . $ruleName . ' does not exist.');
        }
    }

    /**
     * Sanitize the input parameters.
     */
    private function sanitize(string $method, array $params): array
    {
        $sanitized = [];

        foreach ($params as $key => $value) {
            $sanitized[$key] = filter_input($method === 'GET' ? INPUT_GET : INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $sanitized;
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
