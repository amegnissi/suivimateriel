<?php

namespace App\Entity\Courrier;

use App\Entity\Employe;
use App\Repository\Courrier\AffectationCourrierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AffectationCourrierRepository::class)]
class AffectationCourrier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]


    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    private ?Courrier $courrier = null;

    #[ORM\ManyToOne(inversedBy: 'affectationsRecu')]
    private ?Employe $destinataire = null;

    #[ORM\ManyToOne(inversedBy: 'affectationsEnvoyes')]
    private ?Employe $expediteur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAffectation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateLimiteTraitement = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $recu = null;

    #[ORM\Column(nullable: true)]
    private ?bool $traite = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    private ?Statut $statut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateTraitement = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ObservationsTraitement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fichierTraitement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourrier(): ?Courrier
    {
        return $this->courrier;
    }

    public function setCourrier(?Courrier $courrier): static
    {
        $this->courrier = $courrier;

        return $this;
    }

    public function getDestinataire(): ?Employe
    {
        return $this->destinataire;
    }

    public function setDestinataire(?Employe $destinataire): static
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    public function getExpediteur(): ?Employe
    {
        return $this->expediteur;
    }

    public function setExpediteur(?Employe $expediteur): static
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    public function getDateAffectation(): ?\DateTimeInterface
    {
        return $this->dateAffectation;
    }

    public function setDateAffectation(?\DateTimeInterface $dateAffectation): static
    {
        $this->dateAffectation = $dateAffectation;

        return $this;
    }

    public function getDateLimiteTraitement(): ?\DateTimeInterface
    {
        return $this->dateLimiteTraitement;
    }

    public function setDateLimiteTraitement(?\DateTimeInterface $dateLimiteTraitement): static
    {
        $this->dateLimiteTraitement = $dateLimiteTraitement;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): static
    {
        $this->observation = $observation;

        return $this;
    }

    public function isRecu(): ?bool
    {
        return $this->recu;
    }

    public function setRecu(?bool $recu): static
    {
        $this->recu = $recu;

        return $this;
    }

    public function isTraite(): ?bool
    {
        return $this->traite;
    }

    public function setTraite(?bool $traite): static
    {
        $this->traite = $traite;

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateTraitement(): ?\DateTimeInterface
    {
        return $this->dateTraitement;
    }

    public function setDateTraitement(?\DateTimeInterface $dateTraitement): static
    {
        $this->dateTraitement = $dateTraitement;

        return $this;
    }

    public function getObservationsTraitement(): ?string
    {
        return $this->ObservationsTraitement;
    }

    public function setObservationsTraitement(?string $ObservationsTraitement): static
    {
        $this->ObservationsTraitement = $ObservationsTraitement;

        return $this;
    }

    public function getFichierTraitement(): ?string
    {
        return $this->fichierTraitement;
    }

    public function setFichierTraitement(?string $fichierTraitement): static
    {
        $this->fichierTraitement = $fichierTraitement;

        return $this;
    }
}
