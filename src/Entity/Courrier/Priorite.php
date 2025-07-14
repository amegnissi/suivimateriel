<?php

namespace App\Entity\Courrier;

use App\Repository\PrioriteRepository;
use App\traits\AttributsCommunsTraits;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: PrioriteRepository::class)]
class Priorite
{

    use AttributsCommunsTraits;

    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    private ?string $id = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(nullable: true)]
    private ?int $delaiTraitementMoyen = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $couleur = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelleUnique = null;
    use AttributsCommunsTraits;
    public function getId(): ?string
    {
        return $this->id;
    }


    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }



    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDelaiTraitementMoyen(): ?int
    {
        return $this->delaiTraitementMoyen;
    }

    public function setDelaiTraitementMoyen(?int $delaiTraitementMoyen): static
    {
        $this->delaiTraitementMoyen = $delaiTraitementMoyen;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): static
    {
        $this->couleur = $couleur;

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

    public function getLibelleUnique(): ?string
    {
        return $this->libelleUnique;
    }

    public function setLibelleUnique(?string $libelleUnique): static
    {
        $this->libelleUnique = $libelleUnique;

        return $this;
    }



}
