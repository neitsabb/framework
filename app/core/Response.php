<?php

namespace App\Core;

class Response
{
    /**
     * @var string|null $content - The content of the response.
     */
    private ?string $content;

    /**
     * @var int $statusCode - The status code of the response.
     */
    private int $statusCode;

    /**
     * @var array $headers - The headers of the response.
     */
    private array $headers;

    public function __construct(
        ?string $content = null,
        int $statusCode = 200,
        array $headers = []
    ) {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
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
