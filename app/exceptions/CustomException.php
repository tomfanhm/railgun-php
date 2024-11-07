<?php

declare(strict_types=1);

namespace App\Exceptions;

class CustomException extends BaseException
{
    public function __construct(int $code, string $message, string $description)
    {
        $this->code = $code;
        $this->message = $message;
        $this->description = $description;
    }
}
