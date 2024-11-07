<?php

declare(strict_types=1);

namespace App\Exceptions;

class ApiRateLimitExceededException extends BaseException
{
    public function __construct()
    {
        $this->code = 429;
        $this->message = 'API rate limit exceeded';
        $this->description = 'You have exceeded the rate limit for this API. Please try again later.';
    }
}
