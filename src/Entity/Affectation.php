<?php

namespace App\Entity;

use App\Repository\AffectationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AffectationRepository::class)]
class Affectation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAffectation = null;

    #[ORM\ManyToOne]
    private ?LieuAffectation $lieuAffectation = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    private ?Materiel $materiel = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    private ?SocieteService $societe = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    private ?Employe $employe = null;

    /**
     * @var Collection<int, Maintenance>
     */
    #[ORM\OneToMany(targetEntity: Maintenance::class, mappedBy: 'affectation')]
    private Collection $maintenances;

    public function __construct()
    {
        $this->maintenances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLieuAffectation(): ?LieuAffectation
    {
        return $this->lieuAffectation;
    }
    
    public function setLieuAffectation(?LieuAffectation $lieuAffectation): static
    {
        $this->lieuAffectation = $lieuAffectation;
        return $this;
    }

    public function getMateriel(): ?Materiel
    {
        return $this->materiel;
    }

    public function setMateriel(?Materiel $materiel): static
    {
        $this->materiel = $materiel;

        return $this;
    }

    public function getSociete(): ?SocieteService
    {
        return $this->societe;
    }

    public function setSociete(?SocieteService $societe): static
    {
        $this->societe = $societe;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): static
    {
        $this->employe = $employe;

        return $this;
    }

    /**
     * @return Collection<int, Maintenance>
     */
    public function getMaintenances(): Collection
    {
        return $this->maintenances;
    }

    /*
    public function addMaintenance(Maintenance $maintenance): static
    {
        if (!$this->maintenances->contains($maintenance)) {
            $this->maintenances->add($maintenance);
            $maintenance->setAffectation($this);
        }

        return $this;
    }

    public function removeMaintenance(Maintenance $maintenance): static
    {
        if ($this->maintenances->removeElement($maintenance)) {
            // set the owning side to null (unless already changed)
            if ($maintenance->getAffectation() === $this) {
                $maintenance->setAffectation(null);
            }
        }

        return $this;
    }*/
}
