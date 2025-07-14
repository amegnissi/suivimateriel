<?php

namespace App\Entity\Courrier;

use App\traits\AttributsCommunsTraits;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: "App\Repository\TypeCritereClassificationRepository")]

class TypeCritereClassification
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    private ?string $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: TypeCritereClassification::class)]
    #[ORM\JoinColumn(name: "type_critere_parent_id", referencedColumnName: "id", nullable: true)]
    private ?TypeCritereClassification $typeCritereParent = null;

    #[ORM\Column(type: "string", length: 7, nullable: true)]
    private ?string $couleur;

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

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

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



    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getTypeCritereParent(): ?self
    {
        return $this->typeCritereParent;
    }

    public function setTypeCritereParent(?self $typeCritereParent): static
    {
        $this->typeCritereParent = $typeCritereParent;

        return $this;
    }
}
