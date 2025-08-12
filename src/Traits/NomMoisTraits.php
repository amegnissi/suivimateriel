<?php

namespace App\Traits;
use Doctrine\ORM\Mapping as ORM;
trait NomMoisTraits
{
    #[ORM\Column(nullable: true)]
    private ?int $mois = null;

    public function getMois(): ?int
    {
        return $this->mois;
    }

    public function setMois(?int $mois): static
    {
        $this->mois = $mois;

        return $this;
    }

    public function getNomMois(): ?string
    {
        $moisChoices = array_flip(self::getMoisChoices());

        return $moisChoices[$this->mois] ?? null;
    }

    public static function getMoisChoices(): array
    {
        return [
            'Janvier' => 1,
            'Février' => 2,
            'Mars' => 3,
            'Avril' => 4,
            'Mai' => 5,
            'Juin' => 6,
            'Juillet' => 7,
            'Août' => 8,
            'Septembre' => 9,
            'Octobre' => 10,
            'Novembre' => 11,
            'Décembre' => 12,
        ];
    }
}
