<?php

namespace App\Entity\Emploie;

use App\Repository\Emploie\OperationEmploieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OperationEmploieRepository::class)]
class OperationEmploie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateOperation = null;

    #[ORM\ManyToOne(inversedBy: 'operationEmploies')]
    private ?Emploie $designation = null;

    #[ORM\ManyToOne(inversedBy: 'operationEmploies')]
    private ?Caisse $caisse = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantAPayer = null;

    #[ORM\Column(nullable: true)]
    private ?float $retenue = null;

    #[ORM\Column(nullable: true)]
    private ?int $mois = null;

    #[ORM\Column]
    private ?int $annee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDateOperation(): ?\DateTime
    {
        return $this->dateOperation;
    }

    public function setDateOperation(\DateTime $dateOperation): static
    {
        $this->dateOperation = $dateOperation;

        return $this;
    }

    public function getDesignation(): ?Emploie
    {
        return $this->designation;
    }

    public function setDesignation(?Emploie $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getCaisse(): ?Caisse
    {
        return $this->caisse;
    }

    public function setCaisse(?Caisse $caisse): static
    {
        $this->caisse = $caisse;

        return $this;
    }

    public function getMontantAPayer(): ?float
    {
        return $this->montantAPayer;
    }

    public function setMontantAPayer(?float $montantAPayer): static
    {
        $this->montantAPayer = $montantAPayer;

        return $this;
    }

    public function getRetenue(): ?float
    {
        return $this->retenue;
    }

    public function setRetenue(?float $retenue): static
    {
        $this->retenue = $retenue;

        return $this;
    }

    public function getMois(): ?int
    {
        return $this->mois;
    }

    public function setMois(?int $mois): static
    {
        $this->mois = $mois;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }
}
