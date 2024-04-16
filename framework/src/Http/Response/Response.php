<?php

namespace Neitsab\Framework\Http\Response;

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

    public function send(): void
    {
        echo $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
