<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $telephonePersonnel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephoneCorporate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $copieCarteId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $copieDiplome = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $certificatAcquiteVisuel = null;

    #[ORM\Column(nullable: true)]
    private ?bool $depart = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\OneToOne(mappedBy: 'employe', cascade: ['persist', 'remove'])]
    private ?DepartEmploye $departEmploye = null;

    #[ORM\ManyToOne(inversedBy: 'employe')]
    private ?Poste $poste = null;

    /**
     * @var Collection<int, Affectation>
     */
    #[ORM\OneToMany(targetEntity: Affectation::class, mappedBy: 'employe')]
    private Collection $affectations;

    public function __construct()
    {
        $this->affectations = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephonePersonnel(): ?string
    {
        return $this->telephonePersonnel;
    }

    public function setTelephonePersonnel(string $telephonePersonnel): static
    {
        $this->telephonePersonnel = $telephonePersonnel;

        return $this;
    }

    public function getTelephoneCorporate(): ?string
    {
        return $this->telephoneCorporate;
    }

    public function setTelephoneCorporate(?string $telephoneCorporate): static
    {
        $this->telephoneCorporate = $telephoneCorporate;

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

    public function getCopieCarteId(): ?string
    {
        return $this->copieCarteId;
    }

    public function setCopieCarteId(string $copieCarteId): static
    {
        $this->copieCarteId = $copieCarteId;

        return $this;
    }

    public function getCopieDiplome(): ?string
    {
        return $this->copieDiplome;
    }

    public function setCopieDiplome(?string $copieDiplome): static
    {
        $this->copieDiplome = $copieDiplome;

        return $this;
    }

    public function getCertificatAcquiteVisuel(): ?string
    {
        return $this->certificatAcquiteVisuel;
    }

    public function setCertificatAcquiteVisuel(?string $certificatAcquiteVisuel): static
    {
        $this->certificatAcquiteVisuel = $certificatAcquiteVisuel;

        return $this;
    }

    public function isDepart(): ?bool
    {
        return $this->depart;
    }

    public function setDepart(?bool $depart): static
    {
        $this->depart = $depart;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDepartEmploye(): ?DepartEmploye
    {
        return $this->departEmploye;
    }

    public function setDepartEmploye(?DepartEmploye $departEmploye): static
    {
        // unset the owning side of the relation if necessary
        if ($departEmploye === null && $this->departEmploye !== null) {
            $this->departEmploye->setEmploye(null);
        }

        // set the owning side of the relation if necessary
        if ($departEmploye !== null && $departEmploye->getEmploye() !== $this) {
            $departEmploye->setEmploye($this);
        }

        $this->departEmploye = $departEmploye;

        return $this;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): static
    {
        $this->poste = $poste;

        return $this;
    }

    /**
     * @return Collection<int, Affectation>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): static
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations->add($affectation);
            $affectation->setEmploye($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): static
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getEmploye() === $this) {
                $affectation->setEmploye(null);
            }
        }

        return $this;
    }
}
