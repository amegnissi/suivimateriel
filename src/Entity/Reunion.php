<?php

namespace App\Entity;

use App\Repository\ReunionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReunionRepository::class)]
class Reunion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reunions')]
    private ?Personne $participant = null;

    #[ORM\ManyToOne(inversedBy: 'reunions')]
    private ?Evenements $events = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estArrive = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParticipant(): ?Personne
    {
        return $this->participant;
    }

    public function setParticipant(?Personne $participant): static
    {
        $this->participant = $participant;

        return $this;
    }

    public function getEvents(): ?Evenements
    {
        return $this->events;
    }

    public function setEvents(?Evenements $events): static
    {
        $this->events = $events;

        return $this;
    }

    public function isEstArrive(): ?bool
    {
        return $this->estArrive;
    }

    public function setEstArrive(?bool $estArrive): static
    {
        $this->estArrive = $estArrive;

        return $this;
    }
}
