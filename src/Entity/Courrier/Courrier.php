<?php

namespace App\Entity\Courrier;

use App\Repository\courrier\CourrierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourrierRepository::class)]
class Courrier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referenceInterne = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signataire = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateArivee = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateSignature = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $objet = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = true;

    #[ORM\ManyToOne(inversedBy: 'courriers')]
    private ?Partenaire $partenaire = null;

    #[ORM\ManyToOne(inversedBy: 'courriers')]
    private ?Statut $Statut = null;

    #[ORM\ManyToOne(inversedBy: 'courriers')]
    private ?TypeCourrier $typeCourrier = null;

    #[ORM\ManyToOne(inversedBy: 'courriers')]
    private ?NatureCourrier $nature = null;

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

    public function getReferenceInterne(): ?string
    {
        return $this->referenceInterne;
    }

    public function setReferenceInterne(?string $referenceInterne): static
    {
        $this->referenceInterne = $referenceInterne;

        return $this;
    }

    public function getSignataire(): ?string
    {
        return $this->signataire;
    }

    public function setSignataire(?string $signataire): static
    {
        $this->signataire = $signataire;

        return $this;
    }

    public function getDateArivee(): ?\DateTimeInterface
    {
        return $this->dateArivee;
    }

    public function setDateArivee(?\DateTimeInterface $dateArivee): static
    {
        $this->dateArivee = $dateArivee;

        return $this;
    }

    public function getDateSignature(): ?\DateTimeInterface
    {
        return $this->dateSignature;
    }

    public function setDateSignature(?\DateTimeInterface $dateSignature): static
    {
        $this->dateSignature = $dateSignature;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(?string $objet): static
    {
        $this->objet = $objet;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Partenaire $partenaire): static
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->Statut;
    }

    public function setStatut(?Statut $Statut): static
    {
        $this->Statut = $Statut;

        return $this;
    }

    public function getTypeCourrier(): ?TypeCourrier
    {
        return $this->typeCourrier;
    }

    public function setTypeCourrier(?TypeCourrier $typeCourrier): static
    {
        $this->typeCourrier = $typeCourrier;

        return $this;
    }

    public function getNature(): ?NatureCourrier
    {
        return $this->nature;
    }

    public function setNature(?NatureCourrier $nature): static
    {
        $this->nature = $nature;

        return $this;
    }

    public function getFichier()
    {
        return  '/uploads/courriers/' . $this->url;
    }
}
