<?php

namespace App\Entity;

use App\Repository\LieuAffectationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LieuAffectationRepository::class)]
class LieuAffectation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null; // Nom du lieu ou service

    #[ORM\Column(length: 10)]
    private ?string $type = null; // 'interne' ou 'externe'

    #[ORM\ManyToOne(targetEntity: SocieteService::class, inversedBy: 'lieux')]
    private ?SocieteService $societeService = null; // Uniquement si type = 'externe'

    #[ORM\ManyToOne(targetEntity: Entreprise::class)]
    private ?Entreprise $entreprise = null; // Uniquement si type = 'interne'

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSocieteService(): ?SocieteService
    {
        return $this->societeService;
    }

    public function setSocieteService(?SocieteService $societeService): static
    {
        $this->societeService = $societeService;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }
}
