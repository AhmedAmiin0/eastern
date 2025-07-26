<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CurrencyDto
{
    #[Assert\NotBlank(message: 'Currency name is required')]
    #[Assert\Length(min: 1, max: 255, minMessage: 'Currency name must be at least {{ limit }} characters long', maxMessage: 'Currency name cannot be longer than {{ limit }} characters')]

    private string $name;

    #[Assert\NotBlank(message: 'Currency symbol is required')]
    #[Assert\Length(min: 1, max: 10, minMessage: 'Currency symbol must be at least {{ limit }} characters long', maxMessage: 'Currency symbol cannot be longer than {{ limit }} characters')]

    private string $symbol;

    public function __construct()
    {
    }

    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->setName($data['name'] ?? '');
        $dto->setSymbol($data['symbol'] ?? '');
        return $dto;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    // Setter methods
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;
        return $this;
    }
} 