<?php

namespace App\Entity\Emploie;

use App\Repository\Emploie\OperationEmploieRepository;
use App\Traits\NomMoisTraits;
use App\Traits\ReferenceTraits;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OperationEmploieRepository::class)]
class OperationEmploie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    use ReferenceTraits;
    use NomMoisTraits;

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

    #[ORM\Column]
    private ?int $annee = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $autorisation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motifAutorisation = null;

    #[ORM\ManyToOne(inversedBy: 'operationEmploies')]
    private ?Periode $periode = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isClotured = null;

    public function __construct()
    {
        $this->isClotured = false;

    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getAutorisation(): ?string
    {
        return $this->autorisation;
    }

    public function setAutorisation(?string $autorisation): static
    {
        $this->autorisation = $autorisation;

        return $this;
    }

    public function getMotifAutorisation(): ?string
    {
        return $this->motifAutorisation;
    }

    public function setMotifAutorisation(?string $motifAutorisation): static
    {
        $this->motifAutorisation = $motifAutorisation;

        return $this;
    }

    public function getPeriode(): ?Periode
    {
        return $this->periode;
    }

    public function setPeriode(?Periode $periode): static
    {
        $this->periode = $periode;

        return $this;
    }

    public function isClotured(): ?bool
    {
        return $this->isClotured;
    }

    public function setIsClotured(?bool $isClotured): static
    {
        $this->isClotured = $isClotured;

        return $this;
    }
}
