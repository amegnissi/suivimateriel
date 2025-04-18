<?php
namespace App\Entity;

use App\Repository\SortieMaterielRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SortieMaterielRepository::class)]
class SortieMateriel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Materiel $materiel = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateSortie = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRetour = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motif = null;

    #[ORM\ManyToOne(targetEntity: Employe::class)]
    private ?Employe $employe = null;

    public function getId(): ?int { return $this->id; }

    public function getMateriel(): ?Materiel { return $this->materiel; }
    public function setMateriel(?Materiel $materiel): static { $this->materiel = $materiel; return $this; }

    public function getDateSortie(): ?\DateTimeInterface { return $this->dateSortie; }
    public function setDateSortie(?\DateTimeInterface $dateSortie): static { $this->dateSortie = $dateSortie; return $this; }

    public function getDateRetour(): ?\DateTimeInterface { return $this->dateRetour; }
    public function setDateRetour(?\DateTimeInterface $dateRetour): static { $this->dateRetour = $dateRetour; return $this; }

    public function getMotif(): ?string { return $this->motif; }
    public function setMotif(?string $motif): static { $this->motif = $motif; return $this; }

    public function getEmploye(): ?Employe { return $this->employe; }
    public function setEmploye(?Employe $employe): static { $this->employe = $employe; return $this; }
}