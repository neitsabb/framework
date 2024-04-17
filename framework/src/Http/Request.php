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

    /**
     * @var mixed $handler - The request handler.
     */
    public mixed $handler;

    /**
     * @var array $handlerArgs - The arguments to pass to the handler.
     */
    public array $handlerArgs = [];

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

    public function validate(array $rules, ?string $model = null): array
    {
        $errors = [];

        foreach ($rules as $key => $rule) {
            $input = $this->input($key) ?? null;

            $rulesParts = explode('|', $rule);

            foreach ($rulesParts as $rulePart) {
                $ruleDetails = explode(':', $rulePart);

                $ruleName = $ruleDetails[0];
                $ruleValue = $ruleDetails[1] ?? null;

                if ($error = $this->validateRule($input, $ruleName, $ruleValue, $key, $model)) {
                    $errors[$key] = $error;
                    break;
                }
            }
        }

        return $errors;
    }

    public function validateRule(
        mixed $input,
        string $ruleName,
        mixed $ruleValue,
        string $key,
        ?string $model
    ) {
        if (!$model && $ruleName === 'unique') {
            throw new \Exception('The unique rule requires a model.');
        }

        switch ($ruleName) {
            case 'required':
                if ($input === null || $input === '' || empty($input)) {
                    return 'The ' . str_replace('_', ' ', $key) . ' field is required.';
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
                if ($model::where($key, $input)->first()) {
                    return 'The ' . $key . ' field must be unique.';
                }
                break;
            case 'same':
                if ($input !== $this->input($ruleValue)) {
                    return 'The ' . str_replace('_', ' ', $key) . ' field must be the same as the ' . $ruleValue . ' field.';
                }
                break;
            case 'alpha_num':
                if (!ctype_alnum($input)) {
                    return 'The ' . $key . ' field must be alphanumeric.';
                }
                break;
            default:
                throw new \Exception('The validation rule ' . $ruleName . ' does not exist.');
        }
    }

    public function session(): SessionInterface
    {
        return $this->session;
    }

    public function getHandlerWithArgs(): array
    {
        return [$this->handler, $this->handlerArgs];
    }

    public function setHandlerWithArgs(mixed $handler, array $args): void
    {
        $this->handler = $handler;
        $this->handlerArgs = $args;
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
