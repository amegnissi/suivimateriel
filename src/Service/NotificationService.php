<?php

namespace App\Service;

use App\Entity\Notification;
use App\Repository\AssuranceRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class NotificationService
{
    private $assuranceRepository;
    private $entrepriseRepository;
    private $entityManager;

    public function __construct(
        AssuranceRepository    $assuranceRepository,
        EntrepriseRepository   $entrepriseRepository,
        EntityManagerInterface $entityManager,
    )
    {
        $this->assuranceRepository = $assuranceRepository;
        $this->entrepriseRepository = $entrepriseRepository;
        $this->entityManager = $entityManager;
    }

    public function genererNotifications()
    {
        $entreprise = $this->entrepriseRepository->find(1);
        if (!$entreprise) {
            return;
        }

        $delaiAlerte = $entreprise->getDelaiAlerte(); // Récupérer le délai depuis l'entreprise
        $dateLimite = (new DateTime())->modify("+$delaiAlerte days");

        $assurances = $this->assuranceRepository->findAssurancesAVenir($dateLimite);

        foreach ($assurances as $assurance) {
            if (!$assurance->isNotificationEnvoyee()) {
                $notification = new Notification();
                $notification->setMessage("L'échéance de " . $assurance->getType() . " pour le véhicule " . $assurance->getMateriel()->getNom() . " arrive à terme.");
                $notification->setDateCreation(new DateTime());
                $notification->setVue(false);

                $this->entityManager->persist($notification);

                // Marquer la notification comme envoyée
                $assurance->setNotificationEnvoyee(true);
            }
        }

        $this->entityManager->flush();
    }
}
