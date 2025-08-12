<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait ReferenceTraits
{
    #[ORM\Column(name: 'reference_systeme',length: 255, nullable: true)]
    private ?string $referenceSysteme = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referenceManuel = null;

    public function getReferenceSysteme(): ?string
    {
        return $this->referenceSysteme;
    }

    public function setReferenceSysteme(string $referenceSysteme): static
    {
        $this->referenceSysteme = $referenceSysteme;

        return $this;
    }

    public function getReferenceManuel(): ?string
    {
        return $this->referenceManuel;
    }

    public function setReferenceManuel(string $referenceManuel): static
    {
        $this->referenceManuel= $referenceManuel;

        return $this;
    }

}
