<?php

declare(strict_types=1);

namespace App\Exceptions;

class BaseException extends \Exception
{
    /**
     * @var string A brief description of the exception.
     */
    protected string $description;

    /**
     * Gets the description of the exception.
     *
     * @return string The description of the exception.
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
