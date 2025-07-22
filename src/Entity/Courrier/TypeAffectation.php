<?php

namespace App\Entity;

use App\Repository\TypeAffectationRepository;
use App\traits\AttributsCommunsTraits;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeAffectationRepository::class)]
class TypeAffectation
{
    use AttributsCommunsTraits;

    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private $code;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $designation;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
