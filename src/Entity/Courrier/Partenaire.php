<?php

namespace App\Entity\Courrier;

use App\Repository\courrier\PartenaireRepository;
use App\Traits\AttributsCommunsTraits;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PartenaireRepository::class)]
class Partenaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomOuRaisonSocial = null;

//    #[Assert\Unique(message: 'Un partenaire existe deja avec ce numéro de téléphone ')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
//    #[Assert\Unique(message: 'Un partenaire existe deja avec cet email ')]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\ManyToOne(inversedBy: 'partenaires')]
    private ?TypePartenaire $typePartenaire = null;

    /**
     * @var Collection<int, Courrier>
     */
    #[ORM\OneToMany(targetEntity: Courrier::class, mappedBy: 'partenaire')]
    private Collection $courriers;

    public function __construct()
    {
        $this->courriers = new ArrayCollection();
    }

    use AttributsCommunsTraits;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomOuRaisonSocial(): ?string
    {
        return $this->nomOuRaisonSocial;
    }

    public function setNomOuRaisonSocial(?string $nomOuRaisonSocial): static
    {
        $this->nomOuRaisonSocial = $nomOuRaisonSocial;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTypePartenaire(): ?TypePartenaire
    {
        return $this->typePartenaire;
    }

    public function setTypePartenaire(?TypePartenaire $typePartenaire): static
    {
        $this->typePartenaire = $typePartenaire;

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
            $courrier->setPartenaire($this);
        }

        return $this;
    }

    public function removeCourrier(Courrier $courrier): static
    {
        if ($this->courriers->removeElement($courrier)) {
            // set the owning side to null (unless already changed)
            if ($courrier->getPartenaire() === $this) {
                $courrier->setPartenaire(null);
            }
        }

        return $this;
    }
}
