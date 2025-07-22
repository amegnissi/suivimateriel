<?php

namespace App\Entity\Courrier;

use App\Repository\courrier\StatutRepository;
use App\Traits\AttributsCommunsTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Courrier>
     */
    #[ORM\OneToMany(targetEntity: Courrier::class, mappedBy: 'Statut')]
    private Collection $courriers;

    /**
     * @var Collection<int, AffectationCourrier>
     */
    #[ORM\OneToMany(targetEntity: AffectationCourrier::class, mappedBy: 'statut')]
    private Collection $affectations;

    public function __construct()
    {
        $this->courriers = new ArrayCollection();
        $this->affectations = new ArrayCollection();
    }

    use AttributsCommunsTraits;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

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

    /**
     * @return Collection<int, Courrier>
     */
    public function getCourriers(): Collection
    {
        return $this->courriers;
    }

    public function addCourrier(Courrier $courrier): static
    {
        if (!$this->courriers->contains($courrier)) {
            $this->courriers->add($courrier);
            $courrier->setStatut($this);
        }

        return $this;
    }

    public function removeCourrier(Courrier $courrier): static
    {
        if ($this->courriers->removeElement($courrier)) {
            // set the owning side to null (unless already changed)
            if ($courrier->getStatut() === $this) {
                $courrier->setStatut(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AffectationCourrier>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(AffectationCourrier $affectation): static
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations->add($affectation);
            $affectation->setStatut($this);
        }

        return $this;
    }

    public function removeAffectation(AffectationCourrier $affectation): static
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getStatut() === $this) {
                $affectation->setStatut(null);
            }
        }

        return $this;
    }
}
