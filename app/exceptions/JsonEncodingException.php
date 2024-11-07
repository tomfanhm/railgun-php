<?php

declare(strict_types=1);

namespace App\Exceptions;

class JsonEncodingException extends BaseException
{
    public function __construct()
    {
        $this->code = 500;
        $this->message = 'JSON encoding error';
        $this->description = 'An error occurred while encoding the JSON response.';
    }
}
