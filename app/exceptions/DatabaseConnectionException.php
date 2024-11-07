<?php

declare(strict_types=1);

namespace App\Exceptions;

class DatabaseConnectionException extends BaseException
{
    public function __construct()
    {
        $this->code = 500;
        $this->message = 'Database connection error';
        $this->description = 'An error occurred while connecting to the database. Please try again later.';
    }
}
