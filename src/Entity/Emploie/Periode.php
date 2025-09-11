<?php

namespace App\Entity\Emploie;

use App\Repository\Emploie\PeriodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeriodeRepository::class)]
class Periode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'periodes')]
    private ?Exercice $exercice = null;

    #[ORM\ManyToOne(inversedBy: 'periodes')]
    private ?Mois $mois = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isCloture = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isArchive = null;

    /**
     * @var Collection<int, Ressource>
     */
    #[ORM\OneToMany(targetEntity: Ressource::class, mappedBy: 'periode')]
    private Collection $ressource;

    /**
     * @var Collection<int, OperationEmploie>
     */
    #[ORM\OneToMany(targetEntity: OperationEmploie::class, mappedBy: 'periode')]
    private Collection $operationEmploies;

    public function __construct()
    {
        $this->ressource = new ArrayCollection();
        $this->operationEmploies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExercice(): ?Exercice
    {
        return $this->exercice;
    }

    public function setExercice(?Exercice $exercice): static
    {
        $this->exercice = $exercice;

        return $this;
    }

    public function getMois(): ?Mois
    {
        return $this->mois;
    }

    public function setMois(?Mois $mois): static
    {
        $this->mois = $mois;

        return $this;
    }

    public function isCloture(): ?bool
    {
        return $this->isCloture;
    }

    public function setIsCloture(?bool $isCloture): static
    {
        $this->isCloture = $isCloture;

        return $this;
    }

    public function isArchive(): ?bool
    {
        return $this->isArchive;
    }

    public function setIsArchive(?bool $isArchive): static
    {
        $this->isArchive = $isArchive;

        return $this;
    }

    /**
     * @return Collection<int, Ressource>
     */
    public function getRessource(): Collection
    {
        return $this->ressource;
    }

    public function addRessource(Ressource $ressource): static
    {
        if (!$this->ressource->contains($ressource)) {
            $this->ressource->add($ressource);
            $ressource->setPeriode($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): static
    {
        if ($this->ressource->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getPeriode() === $this) {
                $ressource->setPeriode(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OperationEmploie>
     */
    public function getOperationEmploies(): Collection
    {
        return $this->operationEmploies;
    }

    public function addOperationEmploie(OperationEmploie $operationEmploie): static
    {
        if (!$this->operationEmploies->contains($operationEmploie)) {
            $this->operationEmploies->add($operationEmploie);
            $operationEmploie->setPeriode($this);
        }

        return $this;
    }

    public function removeOperationEmploie(OperationEmploie $operationEmploie): static
    {
        if ($this->operationEmploies->removeElement($operationEmploie)) {
            // set the owning side to null (unless already changed)
            if ($operationEmploie->getPeriode() === $this) {
                $operationEmploie->setPeriode(null);
            }
        }

        return $this;
    }
}
