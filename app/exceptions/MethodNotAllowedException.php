<?php

declare(strict_types=1);

namespace App\Exceptions;

class MethodNotAllowedException extends BaseException
{
    public function __construct()
    {
        $this->code = 405;
        $this->message = 'Method not allowed';
        $this->description = 'The request method is not allowed for the requested resource.';
    }
}
