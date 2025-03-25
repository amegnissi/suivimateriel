<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AssuranceRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssuranceRepository::class)]
class Assurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Materiel::class, inversedBy: 'assurances', cascade: ['remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Materiel $vehicule = null;

    // Nouveau champ pour indiquer le type d'assurance
    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Choice(choices: ['assurance', 'tvm', 'visite_technique'], message: 'Choisissez un type d\'assurance valide')]
    private string $typeAssurance;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAssuranceDebut = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAssuranceFin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateVisiteTechniqueDebut = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateVisiteTechniqueFin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateTVMDebut = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateTVMFin = null;

    #[ORM\Column(type: 'boolean')]
    private bool $notifEnvoyee = false;

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

    public function getTypeAssurance(): string
    {
        return $this->typeAssurance;
    }

    public function setTypeAssurance(string $typeAssurance): static
    {
        $this->typeAssurance = $typeAssurance;
        return $this;
    }

    public function getDateAssuranceDebut(): ?\DateTimeInterface
    {
        return $this->dateAssuranceDebut;
    }

    public function setDateAssuranceDebut(?\DateTimeInterface $dateAssuranceDebut): static
    {
        $this->dateAssuranceDebut = $dateAssuranceDebut;
        return $this;
    }

    public function getDateAssuranceFin(): ?\DateTimeInterface
    {
        return $this->dateAssuranceFin;
    }

    public function setDateAssuranceFin(?\DateTimeInterface $dateAssuranceFin): static
    {
        $this->dateAssuranceFin = $dateAssuranceFin;
        return $this;
    }

    public function getDateVisiteTechniqueDebut(): ?\DateTimeInterface
    {
        return $this->dateVisiteTechniqueDebut;
    }

    public function setDateVisiteTechniqueDebut(?\DateTimeInterface $dateVisiteTechniqueDebut): static
    {
        $this->dateVisiteTechniqueDebut = $dateVisiteTechniqueDebut;
        return $this;
    }

    public function getDateVisiteTechniqueFin(): ?\DateTimeInterface
    {
        return $this->dateVisiteTechniqueFin;
    }

    public function setDateVisiteTechniqueFin(?\DateTimeInterface $dateVisiteTechniqueFin): static
    {
        $this->dateVisiteTechniqueFin = $dateVisiteTechniqueFin;
        return $this;
    }

    public function getDateTVMDebut(): ?\DateTimeInterface
    {
        return $this->dateTVMDebut;
    }

    public function setDateTVMDebut(?\DateTimeInterface $dateTVMDebut): static
    {
        $this->dateTVMDebut = $dateTVMDebut;
        return $this;
    }

    public function getDateTVMFin(): ?\DateTimeInterface
    {
        return $this->dateTVMFin;
    }

    public function setDateTVMFin(?\DateTimeInterface $dateTVMFin): static
    {
        $this->dateTVMFin = $dateTVMFin;
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
}
