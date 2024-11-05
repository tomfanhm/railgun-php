<?php

declare(strict_types=1);

use App\Core\Controller;

class ErrorController extends Controller
{
    public function index(int $code, string $message, string $description): void
    {
        $params = [
            "code" => (string) $code,
            "message" => $message,
            "description" => $description,
        ];
    }
}
