<?php

declare(strict_types=1);

namespace App\Exceptions;

class UnauthorizedAccessException extends BaseException
{
    public function __construct()
    {
        $this->code = 403;
        $this->message = 'Unauthorized access';
        $this->description = 'You are not authorized to access this resource.';
    }
}
