<?php

namespace App\Core;

class Response
{
    public const HTTP_NOT_OK = 500;

    public const HTTP_OK = 200;

    public const HTTP_METHOD_NOT_ALLOWED = 405;

    public const HTTP_NOT_FOUND = 404;

    public const HTTP_UNAUTHORIZED = 401;

    public const HTTP_FORBIDDEN = 403;

    /**
     * @var string|null $content - The content of the response.
     */
    private ?string $content;

    /**
     * @var int $status - The status code of the response.
     */
    private int $status;

    /**
     * @var array $headers - The headers of the response.
     */
    private array $headers;

    public function __construct(
        ?string $content = null,
        int $status = 200,
        array $headers = []
    ) {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;

        http_response_code($this->status);
    }

    public function send(): void
    {
        echo $this->content;
    }

    // /**
    //  * The function sets the HTTP response code for the current request.
    //  * 
    //  * @param int code The code parameter is an integer that represents the HTTP status code that you
    //  * want to set for the response.
    //  */
    // public function setStatusCode(int $code): void
    // {
    //     http_response_code($code);
    // }

    // /**
    //  * The function redirects the user to a specified URL.
    //  * 
    //  * @param string url The "url" parameter is a string that represents the URL to which the user will
    //  * be redirected.
    //  */
    // public function redirect(string $url): void
    // {
    //     header('Location: ' . $url);
    // }
}
