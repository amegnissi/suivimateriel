<?php

namespace App\Entity\Emploie;

use App\Repository\Emploie\RessourceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $mois = null;

    #[ORM\Column(nullable: true)]
    private ?int $annee = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sourcesPaiement = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantPris = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantRestant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNomMois(): ?string
    {
        $moisChoices = array_flip(self::getMoisChoices());

        return $moisChoices[$this->mois] ?? null;
    }

    public static function getMoisChoices(): array
    {
        return [
            'Janvier' => 1,
            'Février' => 2,
            'Mars' => 3,
            'Avril' => 4,
            'Mai' => 5,
            'Juin' => 6,
            'Juillet' => 7,
            'Août' => 8,
            'Septembre' => 9,
            'Octobre' => 10,
            'Novembre' => 11,
            'Décembre' => 12,
        ];
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }
}
