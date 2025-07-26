<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ApiException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    public function __construct(
        private ValidatorInterface $validator
    ) {}

    public function validateRequestData(string $content, string $dtoClass): object
    {
        $data = json_decode($content, true);

        if (!$data) {
            throw ApiException::badRequest('Invalid JSON');
        }

        $dto = $dtoClass::fromArray($data);
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            throw ApiException::validationFailed($errorMessages);
        }

        return $dto;
    }
} 