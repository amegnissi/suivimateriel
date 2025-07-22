<?php

namespace App\Entity;

use App\Entity\Affectation;
use App\Entity\Courrier\AffectationCourrier;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;

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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephonePersonnel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephoneCorporate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $copieCarteId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $copieDiplome = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $certificatAcquiteVisuel = null;

    #[ORM\Column(nullable: true)]
    private ?bool $depart = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    // Ajout des propriétés File (NON persistées en BDD)
    private ?File $photoFile = null;
    private ?File $copieCarteIdFile = null;
    private ?File $copieDiplomeFile = null;
    private ?File $certificatAcquiteVisuelFile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $contactUrgenceNom = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $contactUrgencePrenom = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $contactUrgenceTelephone = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $contactUrgenceAdresse = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $contactUrgenceLien = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(mappedBy: 'employe', cascade: ['persist'])]
    private ?DepartEmploye $departEmploye = null;

    #[ORM\ManyToOne(inversedBy: 'employe')]
    private ?Poste $poste = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: 'employes')]
    private ?Entreprise $entreprise = null;

    /**
     * @var Collection<int, Affectation>
     */
    #[ORM\OneToMany(targetEntity: Affectation::class, mappedBy: 'employe')]
    private Collection $affectations;

    #[ORM\OneToOne(mappedBy: 'employe', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    /**
     * @var Collection<int, AffectationCourrier>
     */
    #[ORM\OneToMany(targetEntity: AffectationCourrier::class, mappedBy: 'destinataire')]
    private Collection $affectationsRecu;

    /**
     * @var Collection<int, AffectationCourrier>
     */
    #[ORM\OneToMany(targetEntity: AffectationCourrier::class, mappedBy: 'expediteur')]
    private Collection $affectationsEnvoyes;

    public function __construct()
    {
        $this->affectations = new ArrayCollection();
        $this->affectationsRecu = new ArrayCollection();
        $this->affectationsEnvoyes = new ArrayCollection();
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

    public function setPhotoFile(?File $photoFile = null): void
    {
        $this->photoFile = $photoFile;
        if ($photoFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    public function setCopieCarteIdFile(?File $file = null): void
    {
        $this->copieCarteIdFile = $file;
    }

    public function getCopieCarteIdFile(): ?File
    {
        return $this->copieCarteIdFile;
    }

    public function setCopieDiplomeFile(?File $file = null): void
    {
        $this->copieDiplomeFile = $file;
    }

    public function getCopieDiplomeFile(): ?File
    {
        return $this->copieDiplomeFile;
    }

    public function setCertificatAcquiteVisuelFile(?File $file = null): void
    {
        $this->certificatAcquiteVisuelFile = $file;
    }

    public function getCertificatAcquiteVisuelFile(): ?File
    {
        return $this->certificatAcquiteVisuelFile;
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

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): static
    {
        $this->entreprise = $entreprise;
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

    public function getContactUrgenceNom(): ?string
    {
        return $this->contactUrgenceNom;
    }

    public function setContactUrgenceNom(?string $contactUrgenceNom): static
    {
        $this->contactUrgenceNom = $contactUrgenceNom;

        return $this;
    }

    public function getContactUrgencePrenom(): ?string
    {
        return $this->contactUrgencePrenom;
    }

    public function setContactUrgencePrenom(?string $contactUrgencePrenom): static
    {
        $this->contactUrgencePrenom = $contactUrgencePrenom;

        return $this;
    }

    public function getContactUrgenceTelephone(): ?string
    {
        return $this->contactUrgenceTelephone;
    }

    public function setContactUrgenceTelephone(?string $contactUrgenceTelephone): static
    {
        $this->contactUrgenceTelephone = $contactUrgenceTelephone;

        return $this;
    }

    public function getContactUrgenceAdresse(): ?string
    {
        return $this->contactUrgenceAdresse;
    }

    public function setContactUrgenceAdresse(?string $contactUrgenceAdresse): static
    {
        $this->contactUrgenceAdresse = $contactUrgenceAdresse;

        return $this;
    }

    public function getContactUrgenceLien(): ?string
    {
        return $this->contactUrgenceLien;
    }

    public function setContactUrgenceLien(?string $contactUrgenceLien): static
    {
        $this->contactUrgenceLien = $contactUrgenceLien;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setEmploye(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getEmploye() !== $this) {
            $user->setEmploye($this);
        }

        $this->user = $user;

        return $this;
    }

    public function getFullName() {


        return "{$this->prenom} {$this->nom}";
    }

    /**
     * @return Collection<int, Affectation>
     */
    public function getAffectationsRecu(): Collection
    {
        return $this->affectationsRecu;
    }

    public function addAffectationsRecu(AffectationCourrier $affectationsRecu): static
    {
        if (!$this->affectationsRecu->contains($affectationsRecu)) {
            $this->affectationsRecu->add($affectationsRecu);
            $affectationsRecu->setDestinataire($this);
        }

        return $this;
    }

    public function removeAffectationsRecu(AffectationCourrier $affectationsRecu): static
    {
        if ($this->affectationsRecu->removeElement($affectationsRecu)) {
            // set the owning side to null (unless already changed)
            if ($affectationsRecu->getDestinataire() === $this) {
                $affectationsRecu->setDestinataire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AffectationCourrier>
     */
    public function getAffectationsEnvoyes(): Collection
    {
        return $this->affectationsEnvoyes;
    }

    public function addAffectationsEnvoye(AffectationCourrier $affectationsEnvoye): static
    {
        if (!$this->affectationsEnvoyes->contains($affectationsEnvoye)) {
            $this->affectationsEnvoyes->add($affectationsEnvoye);
            $affectationsEnvoye->setExpediteur($this);
        }

        return $this;
    }

    public function removeAffectationsEnvoye(AffectationCourrier $affectationsEnvoye): static
    {
        if ($this->affectationsEnvoyes->removeElement($affectationsEnvoye)) {
            // set the owning side to null (unless already changed)
            if ($affectationsEnvoye->getExpediteur() === $this) {
                $affectationsEnvoye->setExpediteur(null);
            }
        }

        return $this;
    }
}
