<?php

declare(strict_types=1);

namespace App\Core;

use App\Exceptions\JsonEncodingException;

class Response
{
    /**
     * @var int The HTTP status code for the response.
     */
    private int $statusCode;

    /**
     * @var array<string, array<string>> Associative array of headers.
     */
    private array $headers = [];

    /**
     * @var string The body content of the response.
     */
    private string $body = '';

    public function __construct(int $statusCode = 200, array $headers = [], string $body = '')
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * Set the HTTP status code for the response.
     *
     * @param int $statusCode The HTTP status code to set.
     * @return self
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Set a header for the response.
     *
     * @param string $name Header name.
     * @param string $value Header value.
     * @param bool $replace Whether to replace existing headers with the same name.
     * @return self
     */
    public function setHeader(string $name, string $value, bool $replace = true): self
    {
        $normalized = strtolower($name);

        if ($replace) {
            $this->headers[$normalized] = [$value];
        } else {
            $this->headers[$normalized][] = $value;
        }

        return $this;
    }

    /**
     * Set the body content of the response.
     *
     * @param string $body The response body content.
     * @return self
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Send a JSON response.
     *
     * @param mixed $data Data to be JSON-encoded and sent as the response body.
     * @return self
     * @throws \RuntimeException if JSON encoding fails.
     */
    public function json(mixed $data): self
    {
        $this->setHeader('Content-Type', 'application/json');

        $json = json_encode($data);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonEncodingException();
        }

        $this->setBody($json);

        return $this;
    }

    /**
     * Send an XML response.
     *
     * @param string $xmlContent XML content to be sent as the response body.
     * @return self
     */
    public function xml(string $xmlContent): self
    {
        $this->setHeader('Content-Type', 'application/xml');
        $this->setBody($xmlContent);

        return $this;
    }

    /**
     * Send an HTML response.
     *
     * @param string $content HTML content to be sent as the response body.
     * @return self
     */
    public function html(string $content): self
    {
        $this->setHeader('Content-Type', 'text/html');
        $this->setBody($content);

        return $this;
    }

    /**
     * Redirect to a different URL with an optional status code.
     *
     * @param string $url The URL to redirect to.
     * @param int $statusCode HTTP status code for the redirect.
     * @return void
     */
    public function redirect(string $url, int $statusCode = 302): void
    {
        $this->setStatusCode($statusCode);
        $this->setHeader('Location', $url, true);
        $this->send();
        exit();
    }

    /**
     * Send the response headers and body to the client.
     *
     * @return void
     */
    public function send(): void
    {
        // Send HTTP status code
        http_response_code($this->statusCode);

        // Send headers
        foreach ($this->headers as $name => $values) {
            foreach ($values as $value) {
                header("{$name}: {$value}", false);
            }
        }

        // Output body content
        echo $this->body;
    }
}
