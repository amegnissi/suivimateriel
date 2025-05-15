<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AssuranceRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssuranceRepository::class)]
class Assurance {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Materiel::class, inversedBy: 'assurances', cascade: ['remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Materiel $vehicule = null;

    #[ORM\ManyToOne(targetEntity: TypeAssurance::class, inversedBy: 'assurances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeAssurance $typeAssurance = null;
    #[Assert\LessThan(propertyPath: 'dateFin',message:'Cette doit  doit être inférieure à {{ compared_value }}')]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]

    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]

     #[Assert\GreaterThan(propertyPath: 'dateDebut',
     message : 'La date fin ou date expiration ne peut pas être inferieur à la date début
{{ compared_value }}')]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(type: 'boolean')]
    private bool $notifEnvoyee = false;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $montantPaye = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estRenouvelle = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVehicule(): ?Materiel
    {
        return $this->vehicule;
    }

    public function setVehicule(?Materiel $vehicule): static
    {
        $this->vehicule = $vehicule;
        return $this;
    }

    public function getNotifEnvoyee(): bool
    {
        return $this->notifEnvoyee;
    }

    public function setNotifEnvoyee(bool $notifEnvoyee): static
    {
        $this->notifEnvoyee = $notifEnvoyee;
        return $this;
    }

    public function getMateriel(): ?Materiel
    {
        return $this->vehicule;
    }

    public function setMateriel(?Materiel $vehicule): static
    {
        $this->vehicule = $vehicule;
        return $this;
    }

    public function isNotifEnvoyee(): ?bool
    {
        return $this->notifEnvoyee;
    }

    public function getMontantPaye(): ?float
    {
        return $this->montantPaye;
    }

    public function setMontantPaye(?float $montantPaye): static
    {
        $this->montantPaye = $montantPaye;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getTypeAssurance(): ?TypeAssurance
    {
        return $this->typeAssurance;
    }

    public function setTypeAssurance(?TypeAssurance $typeAssurance): static
    {
        $this->typeAssurance = $typeAssurance;

        return $this;
    }

    public function isEstRenouvelle(): ?bool
    {
        return $this->estRenouvelle;
    }

    public function setEstRenouvelle(?bool $estRenouvelle): static
    {
        $this->estRenouvelle = $estRenouvelle;

        return $this;
    }

}
