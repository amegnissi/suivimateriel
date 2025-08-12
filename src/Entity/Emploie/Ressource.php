<?php

namespace App\Entity\Emploie;

use App\Repository\Emploie\RessourceRepository;
use App\Traits\NomMoisTraits;
use App\Traits\ReferenceTraits;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    use ReferenceTraits;
    use NomMoisTraits;

    #[ORM\Column(nullable: true)]
    private ?int $annee = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sourcesPaiement = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantPris = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantRestant = null;



    #[ORM\Column(length: 255, nullable: true)]
    private ?string $autorisation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motifAutorisation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateOperation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getSourcesPaiement(): ?string
    {
        return $this->sourcesPaiement;
    }

    public function setSourcesPaiement(?string $sourcesPaiement): static
    {
        $this->sourcesPaiement = $sourcesPaiement;

        return $this;
    }

    public function getMontantPris(): ?float
    {
        return $this->montantPris;
    }

    public function setMontantPris(?float $montantPris): static
    {
        $this->montantPris = $montantPris;

        return $this;
    }

    public function getMontantRestant(): ?float
    {
        return $this->montantRestant;
    }

    public function setMontantRestant(?float $montantRestant): static
    {
        $this->montantRestant = $montantRestant;

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

    public function getDateOperation(): ?\DateTime
    {
        return $this->dateOperation;
    }

    public function setDateOperation(?\DateTime $dateOperation): static
    {
        $this->dateOperation = $dateOperation;

        return $this;
    }
}
