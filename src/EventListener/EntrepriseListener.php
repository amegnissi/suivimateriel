<?php

namespace App\EventListener;

use Twig\Environment;
use App\Entity\Entreprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class EntrepriseListener
{
    private $twig;
    private $entityManager;

    public function __construct(Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $entreprise = $this->entityManager->getRepository(Entreprise::class)->find(1); // ID 1
    
        if (!$entreprise) {
            // Debug ici pour vérifier si entreprise existe
            dump('Entreprise not found');
        }
    
        // Injecter l'entreprise globalement dans Twig
        $this->twig->addGlobal('entreprise', $entreprise);
    }
}