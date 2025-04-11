<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenoms = null;

    #[ORM\Column(length: 255)]
    private ?string $structure = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    /**
     * @var Collection<int, Reunion>
     */
    #[ORM\OneToMany(targetEntity: Reunion::class, mappedBy: 'participant')]
    private Collection $reunions;

    #[ORM\ManyToOne(inversedBy: 'employe')]
    private ?Structure $entreprise = null;

    public function __construct()
    {
        $this->reunions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(?string $prenoms): static
    {
        $this->prenoms = $prenoms;

        return $this;
    }

    public function getStructure(): ?string
    {
        return $this->structure;
    }

    public function setStructure(string $structure): static
    {
        $this->structure = $structure;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Reunion>
     */
    public function getReunions(): Collection
    {
        return $this->reunions;
    }

    public function addReunion(Reunion $reunion): static
    {
        if (!$this->reunions->contains($reunion)) {
            $this->reunions->add($reunion);
            $reunion->setParticipant($this);
        }

        return $this;
    }

    public function removeReunion(Reunion $reunion): static
    {
        if ($this->reunions->removeElement($reunion)) {
            // set the owning side to null (unless already changed)
            if ($reunion->getParticipant() === $this) {
                $reunion->setParticipant(null);
            }
        }

        return $this;
    }

    public function getFullName()
    {
        return $this->prenoms.' '.strtoupper($this->nom);
    }

    public function getFullInformations()
    {
        $information =  $this->prenoms.' '.strtoupper($this->nom);
        if($this->telephone != null){
            $information .= ' ('.$this->telephone.')';
        }
        return  $information;
    }

    public function getEntreprise(): ?Structure
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Structure $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }
}
