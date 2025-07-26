<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['country'])]
    private string $name;

    #[ORM\Column(type: 'string', length: 10)]
    #[Groups(['country'])]
    private string $symbol;

    #[ORM\OneToMany(mappedBy: 'currency', targetEntity: Country::class)]
    private $countries;

    public function __construct()
    {
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;
        return $this;
    }

    public function getCountries(): \Doctrine\Common\Collections\Collection
    {
        return $this->countries;
    }

    public function addCountry(Country $country): self
    {
        if (!$this->countries->contains($country)) {
            $this->countries->add($country);
            $country->setCurrency($this);
        }
        return $this;
    }

    public function removeCountry(Country $country): self
    {
        if ($this->countries->removeElement($country)) {
            if ($country->getCurrency() === $this) {
                $country->setCurrency(null);
            }
        }
        return $this;
    }
} 