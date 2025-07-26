<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CountryDto
{
    #[Assert\NotBlank(message: 'Name is required')]
    #[Assert\Length(min: 1, max: 255, minMessage: 'Name must be at least {{ limit }} characters long', maxMessage: 'Name cannot be longer than {{ limit }} characters')]

    private $name;

    #[Assert\NotBlank(message: 'Region is required')]
    #[Assert\Length(min: 1, max: 255, minMessage: 'Region must be at least {{ limit }} characters long', maxMessage: 'Region cannot be longer than {{ limit }} characters')]

    private $region;

    #[Assert\NotBlank(message: 'Sub region is required')]
    #[Assert\Length(min: 1, max: 255, minMessage: 'Sub region must be at least {{ limit }} characters long', maxMessage: 'Sub region cannot be longer than {{ limit }} characters')]

    private $subRegion;

    #[Assert\NotBlank(message: 'Demonym is required')]
    #[Assert\Length(min: 1, max: 255, minMessage: 'Demonym must be at least {{ limit }} characters long', maxMessage: 'Demonym cannot be longer than {{ limit }} characters')]

    private $demonym;

    #[Assert\Type(type: 'integer', message: 'Population must be an integer')]

    private $population = null;

    #[Assert\NotNull(message: 'Independent status is required')]
    #[Assert\Type(type: 'boolean', message: 'Independent must be a boolean')]

    private $independant = null;

    #[Assert\NotBlank(message: 'Flag URL is required')]
    #[Assert\Url(message: 'Flag must be a valid URL')]
    #[Assert\Length(max: 255, maxMessage: 'Flag URL cannot be longer than {{ limit }} characters')]

    private $flag;

    
    private $uuid;

    #[Assert\Valid]

    private ?CurrencyDto $currency = null;

    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->setName($data['name'] ?? null);
        $dto->setRegion($data['region'] ?? null);
        $dto->setSubRegion($data['subRegion'] ?? null);
        $dto->setDemonym($data['demonym'] ?? null);
        $dto->setPopulation($data['population'] ?? null);
        $dto->setIndependant($data['independant'] ?? null);
        $dto->setFlag($data['flag'] ?? null);

        if (isset($data['currency']) && is_array($data['currency'])) {
            $dto->setCurrency(CurrencyDto::fromArray($data['currency']));
        }

        return $dto;
    }

    // Getter methods
    public function getName(): ?string
    {
        return $this->name;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getSubRegion(): ?string
    {
        return $this->subRegion;
    }

    public function getDemonym(): ?string
    {
        return $this->demonym;
    }

    public function getPopulation(): ?int
    {
        return $this->population;
    }

    public function getIndependant(): ?bool
    {
        return $this->independant;
    }

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function getCurrency(): ?CurrencyDto
    {
        return $this->currency;
    }

    // Setter methods
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;
        return $this;
    }

    public function setSubRegion(?string $subRegion): self
    {
        $this->subRegion = $subRegion;
        return $this;
    }

    public function setDemonym(?string $demonym): self
    {
        $this->demonym = $demonym;
        return $this;
    }

    public function setPopulation(?int $population): self
    {
        $this->population = $population;
        return $this;
    }

    public function setIndependant(?bool $independant): self
    {
        $this->independant = $independant;
        return $this;
    }

    public function setFlag(?string $flag): self
    {
        $this->flag = $flag;
        return $this;
    }

    public function setCurrency(?CurrencyDto $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }

} 