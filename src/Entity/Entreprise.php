<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ifu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siteWeb = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gerant = null;

    #[ORM\Column(nullable: true)]
    private ?float $kilometrage = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $delaiAssurance = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $delaiTVM = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $delaiVisiteTechnique = null;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Materiel::class)]
    private Collection $materiels;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Employe::class)]
    private Collection $employes;

    public function __construct()
    {
        $this->materiels = new ArrayCollection();
        $this->employes = new ArrayCollection();
    }

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

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

    public function getIfu(): ?string
    {
        return $this->ifu;
    }

    public function setIfu(?string $ifu): static
    {
        $this->ifu = $ifu;

        return $this;
    }

    public function getSiteWeb(): ?string
    {
        return $this->siteWeb;
    }

    public function setSiteWeb(?string $siteWeb): static
    {
        $this->siteWeb = $siteWeb;

        return $this;
    }

    public function getGerant(): ?string
    {
        return $this->gerant;
    }

    public function setGerant(?string $gerant): static
    {
        $this->gerant = $gerant;

        return $this;
    }

    public function getKilometrage(): ?float
    {
        return $this->kilometrage;
    }

    public function setKilometrage(?float $kilometrage): static
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getDelaiAssurance(): ?int
    {
        return $this->delaiAssurance;
    }

    public function setDelaiAssurance(?int $delaiAssurance): self
    {
        $this->delaiAssurance = $delaiAssurance;
        return $this;
    }

    public function getDelaiTVM(): ?int
    {
        return $this->delaiTVM;
    }

    public function setDelaiTVM(?int $delaiTVM): self
    {
        $this->delaiTVM = $delaiTVM;
        return $this;
    }

    public function getDelaiVisiteTechnique(): ?int
    {
        return $this->delaiVisiteTechnique;
    }

    public function setDelaiVisiteTechnique(?int $delaiVisiteTechnique): self
    {
        $this->delaiVisiteTechnique = $delaiVisiteTechnique;
        return $this;
    }


    /**
     * @return Collection<int, Materiel>
     */
    public function getMateriels(): Collection
    {
        return $this->materiels;
    }

    public function addMateriel(Materiel $materiel): static
    {
        if (!$this->materiels->contains($materiel)) {
            $this->materiels->add($materiel);
            $materiel->setEntreprise($this);
        }
        return $this;
    }

    public function removeMateriel(Materiel $materiel): static
    {
        if ($this->materiels->removeElement($materiel)) {
            if ($materiel->getEntreprise() === $this) {
                $materiel->setEntreprise(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Employe>
     */
    public function getEmployes(): Collection
    {
        return $this->employes;
    }

    public function addEmploye(Employe $employe): static
    {
        if (!$this->employes->contains($employe)) {
            $this->employes->add($employe);
            $employe->setEntreprise($this);
        }
        return $this;
    }

    public function removeEmploye(Employe $employe): static
    {
        if ($this->employes->removeElement($employe)) {
            if ($employe->getEntreprise() === $this) {
                $employe->setEntreprise(null);
            }
        }
        return $this;
    }
}
