<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Psr\Log\LoggerInterface;
use App\Exception\ApiException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if (!str_starts_with($request->getPathInfo(), '/api/')) {
            return;
        }

        $this->logger->error('API Exception: ' . $exception->getMessage(), [
            'exception' => $exception,
            'url' => $request->getPathInfo(),
            'method' => $request->getMethod(),
        ]);

        $response = $this->createJsonResponse($exception);
        $event->setResponse($response);
    }

    private function createJsonResponse(\Throwable $exception): JsonResponse
    {
        if ($exception instanceof ApiException) {
            $response = [
                'error' => $exception->getMessage(),
                'status' => $exception->getStatusCode(),
            ];
            
            if (!empty($exception->getErrors())) {
                $response['errors'] = $exception->getErrors();
            }
            
            return new JsonResponse($response, $exception->getStatusCode());
        }

        if ($exception instanceof HttpException) {
            $errorMessage = $exception->getMessage();
            
            if ($exception->getStatusCode() === 404) {
                if (str_contains($errorMessage, 'object not found by')) {
                    $errorMessage = 'Resource not found';
                }
            }
            
            return new JsonResponse([
                'error' => $errorMessage,
                'status' => $exception->getStatusCode(),
            ], $exception->getStatusCode());
        }

        if ($exception instanceof SerializerException) {
            return new JsonResponse([
                'error' => 'Invalid data format',
                'message' => $exception->getMessage(),
            ], 400);
        }

        if ($exception instanceof ValidatorException) {
            return new JsonResponse([
                'error' => 'Validation failed',
                'message' => $exception->getMessage(),
            ], 400);
        }

        if ($exception instanceof \JsonException) {
            return new JsonResponse([
                'error' => 'Invalid JSON format',
                'message' => $exception->getMessage(),
            ], 400);
        }

        if ($exception instanceof \Doctrine\DBAL\Exception) {
            return new JsonResponse([
                'error' => 'Database error',
                'message' => 'An error occurred while processing your request',
            ], 500);
        }

        if ($exception instanceof \Doctrine\ORM\Exception\ORMException) {
            return new JsonResponse([
                'error' => 'Database error',
                'message' => 'An error occurred while processing your request',
            ], 500);
        }

        return new JsonResponse([
            'error' => 'Internal server error',
            'message' => 'An unexpected error occurred',
        ], 500);
    }
} 