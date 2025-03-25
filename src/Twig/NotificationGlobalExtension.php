<?php

namespace App\Twig;

use App\Repository\NotificationRepository;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;

class NotificationGlobalExtension extends AbstractExtension implements GlobalsInterface
{
    private $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function getGlobals(): array
    {
        // Récupérer les notifications non lues
        $notifications = $this->notificationRepository->findUnreadNotifications();

        return [
            'notifications' => $notifications
        ];
    }
}
