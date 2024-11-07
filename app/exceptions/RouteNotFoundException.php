<?php

declare(strict_types=1);

namespace App\Exceptions;

class RouteNotFoundException extends BaseException
{
    public function __construct()
    {
        $this->code = 404;
        $this->message = 'Page not found';
        $this->description = 'The requested page was not found on this server.';
    }
}
