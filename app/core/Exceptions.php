<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Class RouteNotFoundException.
 *
 * Exception thrown when a requested route is not found.
 */
class RouteNotFoundException extends \Exception
{
}

/**
 * Class MethodNotAllowedException.
 *
 * Exception thrown when a requested HTTP method is not allowed.
 */
class MethodNotAllowedException extends \Exception
{
}
/**
 * Class DatabaseConnectionException.
 *
 * Exception thrown when there is a database connection error.
 */
class DatabaseConnectionException extends \Exception
{
}

/**
 * Class ValidationException.
 *
 * Exception thrown when data validation fails.
 */
class ValidationException extends \Exception
{
}

/**
 * Class UnauthorizedAccessException.
 *
 * Exception thrown when an unauthorized access attempt is detected.
 */
class UnauthorizedAccessException extends \Exception
{
}

/**
 * Class SessionExpiredException.
 *
 * Exception thrown when a user session has expired.
 */
class SessionExpiredException extends \Exception
{
}

/**
 * Class ApiRateLimitExceededException.
 *
 * Exception thrown when an API rate limit is exceeded.
 */
class ApiRateLimitExceededException extends \Exception
{
}
