<?php
declare(strict_types=1);

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['country'])]
    private int $id;

    #[ORM\Column(type: 'uuid', unique: true)]
    #[Groups(['country'])]
    private Uuid $uuid;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['country'])]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['country'])]
    private string $region;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['country'])]
    private string $subRegion;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['country'])]
    private string $demonym;

    #[ORM\Column(type: 'bigint')]
    #[Groups(['country'])]
    private int $population;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['country'])]
    private bool $independant;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['country'])]
    private string $flag;

    #[ORM\ManyToOne(targetEntity: Currency::class, inversedBy: 'countries')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['country'])]
    private ?Currency $currency = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): self
    {
        $this->uuid = $uuid;
        return $this;
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

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;
        return $this;
    }

    public function getSubRegion(): string
    {
        return $this->subRegion;
    }

    public function setSubRegion(string $subRegion): self
    {
        $this->subRegion = $subRegion;
        return $this;
    }

    public function getDemonym(): string
    {
        return $this->demonym;
    }

    public function setDemonym(string $demonym): self
    {
        $this->demonym = $demonym;
        return $this;
    }

    public function getPopulation(): int
    {
        return $this->population;
    }

    public function setPopulation(int $population): self
    {
        $this->population = $population;
        return $this;
    }

    public function isIndependant(): bool
    {
        return $this->independant;
    }

    public function setIndependant(bool $independant): self
    {
        $this->independant = $independant;
        return $this;
    }

    public function getFlag(): string
    {
        return $this->flag;
    }

    public function setFlag(string $flag): self
    {
        $this->flag = $flag;
        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;
        return $this;
    }
}