<?php

namespace App\Entity;

use App\Repository\MaintenanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaintenanceRepository::class)]
class Maintenance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $typeMainteance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateIntervention = null;

    #[ORM\Column(nullable: true)]
    private ?float $kilometrageActuel = null;

    #[ORM\Column(nullable: true)]
    private ?float $cout = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $preuve = null;

    #[ORM\ManyToOne(inversedBy: 'maintenances')]
    private ?Affectation $affectation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeMainteance(): ?string
    {
        return $this->typeMainteance;
    }

    public function setTypeMainteance(string $typeMainteance): static
    {
        $this->typeMainteance = $typeMainteance;

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

    public function getDateIntervention(): ?\DateTimeInterface
    {
        return $this->dateIntervention;
    }

    public function setDateIntervention(?\DateTimeInterface $dateIntervention): static
    {
        $this->dateIntervention = $dateIntervention;

        return $this;
    }

    public function getKilometrageActuel(): ?float
    {
        return $this->kilometrageActuel;
    }

    public function setKilometrageActuel(?float $kilometrageActuel): static
    {
        $this->kilometrageActuel = $kilometrageActuel;

        return $this;
    }

    public function getCout(): ?float
    {
        return $this->cout;
    }

    public function setCout(?float $cout): static
    {
        $this->cout = $cout;

        return $this;
    }

    public function getPreuve(): ?string
    {
        return $this->preuve;
    }

    public function setPreuve(?string $preuve): static
    {
        $this->preuve = $preuve;

        return $this;
    }

    public function getAffectation(): ?Affectation
    {
        return $this->affectation;
    }

    public function setAffectation(?Affectation $affectation): static
    {
        $this->affectation = $affectation;

        return $this;
    }
}
