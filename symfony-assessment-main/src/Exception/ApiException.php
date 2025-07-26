<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    private array $errors;

    public function __construct(string $message = '', int $statusCode = 400, array $errors = [], \Throwable $previous = null)
    {
        parent::__construct($statusCode, $message, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public static function validationFailed(array $errors): self
    {
        return new self('Validation failed', 400, $errors);
    }

    public static function notFound(string $message = 'Resource not found'): self
    {
        return new self($message, 404);
    }

    public static function badRequest(string $message = 'Bad request'): self
    {
        return new self($message, 400);
    }

    public static function serverError(string $message = 'Internal server error'): self
    {
        return new self($message, 500);
    }
} 