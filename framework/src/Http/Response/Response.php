<?php

namespace Neitsab\Framework\Http\Response;

class Response
{
    /**
     * @var int HTTP_NOT_OK - The status code for a not ok response.
     */
    public const HTTP_NOT_OK = 500;

    /**
     * @var int HTTP_OK - The status code for a ok response.
     */
    public const HTTP_OK = 200;

    /**
     * @var int HTTP_METHOD_NOT_ALLOWED - The status code for a method not allowed response response.
     */
    public const HTTP_METHOD_NOT_ALLOWED = 405;

    /**
     * @var int HTTP_NOT_FOUND - The status code for a not found response.
     */
    public const HTTP_NOT_FOUND = 404;

    /**
     * @var int HTTP_UNAUTHORIZED - The status code for a unauthorized response.
     */
    public const HTTP_UNAUTHORIZED = 401;

    /**
     * @var int HTTP_FORBIDDEN - The status code for a forbidden response.
     */
    public const HTTP_FORBIDDEN = 403;

    /**
     * @var string|null $content - The content of the response.
     */
    private ?string $content;

    /**
     * @var int $status - The status code of the response.
     */
    protected int $status;

    /**
     * @var array $headers - The headers of the response.
     */
    protected array $headers;

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

    /**
     * Send the response
     * 
     * @return void
     */
    public function send(): void
    {
        echo $this->content;
    }

    /**
     * Set the status code
     * 
     * @param int $status - the status code
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
