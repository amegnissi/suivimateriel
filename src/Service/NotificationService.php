<?php

namespace App\Service;

use App\Entity\Notification;
use App\Repository\AssuranceRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class NotificationService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createNotification(string $message): void
    {
        $notification = new Notification();
        $notification->setMessage($message);
        $notification->setVue(false);
        $notification->setDateCreation(new \DateTime());

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}
