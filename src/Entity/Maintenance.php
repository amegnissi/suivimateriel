<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Entity\TypeMaintenance;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MaintenanceRepository;

#[ORM\Entity(repositoryClass: MaintenanceRepository::class)]
class Maintenance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'mainteannces')]
    private ?TypeMaintenance $typeMaintenance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateIntervention = null;

    #[ORM\Column(nullable: true)]
    private ?float $kilometrageActuel = null;

    #[ORM\Column(nullable: true)]
    private ?float $kilometragePrevisionnel = null;

    #[ORM\Column(nullable: true)]
    private ?float $cout = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $preuve = null;

    #[ORM\ManyToOne(targetEntity: Materiel::class, inversedBy: 'maintenances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Materiel $materiel = null;

    #[ORM\Column(type: 'boolean')]
    private bool $statut = false; // Nouveau champ : false = en cours, true = terminé

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeMaintenance(): ?TypeMaintenance
    {
        return $this->typeMaintenance;
    }
    
    public function setTypeMaintenance(?TypeMaintenance $typeMaintenance): static
    {
        $this->typeMaintenance = $typeMaintenance;
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

    public function getKilometragePrevisionnel(): ?float
    {
        return $this->kilometragePrevisionnel;
    }

    public function setKilometragePrevisionnel(?float $kilometragePrevisionnel): static
    {
        $this->kilometragePrevisionnel = $kilometragePrevisionnel;
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

    public function getMateriel(): ?Materiel
    {
        return $this->materiel;
    }

    public function setMateriel(?Materiel $materiel): static
    {
        $this->materiel = $materiel;
        return $this;
    }

    public function getStatut(): bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }
}
