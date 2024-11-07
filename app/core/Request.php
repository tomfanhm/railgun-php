<?php

declare(strict_types=1);

namespace App\Core;

class Request
{
    /**
     * @var array An array containing the headers from the request.
     */
    private array $headers;

    public function __construct()
    {
        $this->headers = apache_request_headers();
    }

    /**
     * Retrieves the path from the current request URI.
     *
     * This method extracts the path component from the request URI, excluding any query string.
     * If the request URI is not set, it defaults to "/".
     *
     * @return string The path component of the request URI.
     */
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }

        return substr($path, 0, $position);
    }

    /**
     * Retrieves the HTTP request method used for the current request.
     *
     * @return string The HTTP request method in lowercase (e.g., 'get', 'post', 'put', 'delete').
     */
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Check if the current request method is GET.
     *
     * @return bool Returns true if the request method is GET, false otherwise.
     */
    public function isGet(): bool
    {
        return $this->getMethod() === 'get';
    }

    /**
     * Checks if the current request method is POST.
     *
     * @return bool Returns true if the request method is POST, false otherwise.
     */
    public function isPost(): bool
    {
        return $this->getMethod() === 'post';
    }

    /**
     * Gets all HTTP headers.
     *
     * @return array An associative array of HTTP headers.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Gets a specific HTTP header by name.
     *
     * @param string $name The name of the HTTP header.
     * @param string $default The default value if the header is not found.
     * @return string The value of the HTTP header or the default value.
     */
    public function getHeader(string $name, string $default = ''): string
    {
        return $this->headers[$name] ?? $default;
    }

    /**
     * Checks if the request is an AJAX request.
     *
     * @return bool True if the request is an AJAX request, false otherwise.
     */
    public function isAjax(): bool
    {
        return $this->getHeader('X-Requested-With') === 'XMLHttpRequest';
    }

    /**
     * Retrieves the request body parameters and sanitizes them.
     *
     * This method processes the request body based on the HTTP method used.
     * The sanitization is done using the FILTER_SANITIZE_SPECIAL_CHARS filter to prevent XSS attacks.
     *
     * @return array The sanitized request body parameters.
     */
    public function getBody(): array
    {
        $body = [];
        if ($this->getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->getMethod() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
}
