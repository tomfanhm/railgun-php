<?php

declare(strict_types=1);

namespace App\Core;

class Session
{
    /**
     * @var string The key used to store the CSRF token in the session.
     */
    private string $csrfTokenKey = 'csrf_token';
    /**
     * @var string The key used to store flash data in the session.
     */
    private string $flashDataKey = 'flash_data';

    public function __construct()
    {
        session_start();
        $this->removeOldFlashData();
    }

    public function __destruct()
    {
        $this->clearFlashData();
    }

    /**
     * Retrieve a value from the session.
     *
     * @param string $key The key of the session variable to retrieve.
     * @param mixed $default The default value to return if the session variable does not exist. Default is null.
     * @return mixed The value of the session variable, or the default value if the session variable does not exist.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Sets a session variable with the given key and value.
     *
     * @param string $key The key to identify the session variable.
     * @param mixed $value The value to be stored in the session variable.
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Check if a session variable is set.
     *
     * @param string $key The key of the session variable to check.
     * @return bool Returns true if the session variable is set, false otherwise.
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Removes a session variable.
     *
     * @param string $key The key of the session variable to remove.
     * @return void
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroys the current session and clears all session data.
     *
     * @return void
     */
    public function destroy(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Sets a flash message in the session.
     *
     * @param string $key The key under which the flash message will be stored.
     * @param mixed $value The flash message content to be stored.
     * @return void
     */
    public function setFlash(string $key, mixed $value): void
    {
        $_SESSION[$this->flashDataKey][$key] = [
            'value' => $value,
            'remove' => false,
        ];
    }

    /**
     * Retrieve a flash message from the session.
     *
     * @param string $key The key of the flash message to retrieve.
     * @param mixed $default The default value to return if the flash message does not exist.
     * @return mixed The flash message value or the default value if the key does not exist.
     */
    public function getFlash(string $key, mixed $default = null): mixed
    {
        if (isset($_SESSION[$this->flashDataKey][$key])) {
            // Mark flash data to be removed after it's been read
            $_SESSION[$this->flashDataKey][$key]['remove'] = true;

            return $_SESSION[$this->flashDataKey][$key]['value'];
        }

        return $default;
    }

    /**
     * Generates a CSRF token and stores it in the session.
     *
     * @return string The generated CSRF token.
     */
    public function generateCsrfToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION[$this->csrfTokenKey] = $token;

        return $token;
    }

    /**
     * Retrieves the CSRF token from the session.
     *
     * @return string The CSRF token stored in the session.
     */
    public function getCsrfToken(): string
    {
        return $_SESSION[$this->csrfTokenKey];
    }

    /**
     * Validates the provided CSRF token against the token stored in the session.
     *
     * @param string $token The CSRF token to validate.
     * @return bool Returns true if the provided token matches the token stored in the session, false otherwise.
     */
    public function validateCsrfToken(string $token): bool
    {
        return hash_equals($_SESSION[$this->csrfTokenKey] ?? '', $token);
    }

    /**
     * Clears flash data from the session.
     *
     * @return void
     */
    private function clearFlashData(): void
    {
        if (isset($_SESSION[$this->flashDataKey])) {
            foreach ($_SESSION[$this->flashDataKey] as $key => $flash) {
                if ($flash['remove']) {
                    unset($_SESSION[$this->flashDataKey][$key]);
                }
            }
        }
    }

    /**
     * Marks all flash messages for removal in the next request.
     *
     * @return void
     */
    private function removeOldFlashData(): void
    {
        if (isset($_SESSION[$this->flashDataKey])) {
            foreach ($_SESSION[$this->flashDataKey] as $key => &$flash) {
                $flash['remove'] = true;
            }
        }
    }
}
