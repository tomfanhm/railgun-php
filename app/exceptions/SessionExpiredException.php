<?php

declare(strict_types=1);

namespace App\Exceptions;

class SessionExpiredException extends BaseException
{
    public function __construct()
    {
        $this->code = 401;
        $this->message = 'Session expired';
        $this->description = 'The session has expired and the user needs to log in again.';
    }
}
