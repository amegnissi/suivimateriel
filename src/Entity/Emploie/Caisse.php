<?php

namespace App\Entity\Emploie;

use App\Repository\Emploie\CaisseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaisseRepository::class)]
class Caisse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, OperationEmploie>
     */
    #[ORM\OneToMany(targetEntity: OperationEmploie::class, mappedBy: 'caisse')]
    private Collection $operationEmploies;

    public function __construct()
    {
        $this->operationEmploies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $operationEmploie->setCaisse($this);
        }

        return $this;
    }

    public function removeOperationEmploie(OperationEmploie $operationEmploie): static
    {
        if ($this->operationEmploies->removeElement($operationEmploie)) {
            // set the owning side to null (unless already changed)
            if ($operationEmploie->getCaisse() === $this) {
                $operationEmploie->setCaisse(null);
            }
        }

        return $this;
    }
}
